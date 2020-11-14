<?php

namespace TmpFileManager;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandler;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\Listener\DeferredPurgeListener;
use TmpFileManager\Listener\GarbageCollectionListener;
use TmpFileManager\Listener\UnclosedResourcesListener;
use TmpFileManager\Exception\TmpFileCreateException;
use TmpFileManager\Exception\TmpFileContextCallbackException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TmpFileManager implements TmpFileManagerInterface, TmpFileManagerServicesInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var TmpFileHandlerInterface
     */
    private $tmpFileHandler;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(
        ConfigInterface $config = null,
        ContainerInterface $container = null,
        TmpFileHandlerInterface $tmpFileHandler = null,
        EventDispatcherInterface $eventDispatcher = null
    ) {
        $this->config = $config ?? new Config(new ConfigBuilder());
        $this->container = $container ?? new Container();
        $this->tmpFileHandler = $tmpFileHandler ?? new TmpFileHandler(new Filesystem());
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();

        $this->addEventListeners();

        $this->eventDispatcher->dispatch(new TmpFileManagerStartEvent($this));
    }

    private function addEventListeners(): void
    {
        $this->eventDispatcher->addListener(TmpFileManagerStartEvent::class, new GarbageCollectionListener());
        $this->eventDispatcher->addListener(TmpFileManagerStartEvent::class, new DeferredPurgeListener());
        $this->eventDispatcher->addListener(TmpFileManagerPurgeEvent::class, new UnclosedResourcesListener());
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getTmpFileHandler(): TmpFileHandlerInterface
    {
        return $this->tmpFileHandler;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @return TmpFileInterface
     *
     * @throws TmpFileCreateException
     */
    public function createTmpFile(): TmpFileInterface
    {
        $dir = $this->config->getTmpFileDirectory();
        $prefix = $this->config->getTmpFilePrefix();

        $filename = $this->tmpFileHandler->getTmpFileName($dir, $prefix);

        try {
            $tmpFile = $this->makeTmpFile($filename);
        } catch (\ReflectionException $e) {
            throw new TmpFileCreateException(
                $e->getMessage()
            );
        }

        $this->container->addTmpFile($tmpFile);

        $this->eventDispatcher->dispatch(new TmpFileCreateEvent($tmpFile));

        return $tmpFile;
    }

    /**
     * @param string $realPath
     *
     * @return TmpFileInterface
     *
     * @throws \ReflectionException
     */
    private function makeTmpFile(string $realPath): TmpFileInterface
    {
        $tmpFileReflection = new \ReflectionClass(TmpFile::class);

        /** @var TmpFile $tmpFile */
        $tmpFile = $tmpFileReflection->newInstanceWithoutConstructor();

        $filename = $tmpFileReflection->getProperty('filename');

        $filename->setAccessible(true);

        $filename->setValue($tmpFile, $realPath);

        return $tmpFile;
    }

    /**
     * @param callable $callback
     *
     * @return mixed
     *
     * @throws TmpFileContextCallbackException
     */
    public function createTmpFileContext(callable $callback)
    {
        $tmpFile = $this->createTmpFile();

        try {
            $result = $callback($tmpFile);

            if ($result instanceof TmpFileInterface) {
                throw new TmpFileContextCallbackException(
                    sprintf("You can't return object like %s from context callback function", TmpFileInterface::class)
                );
            }

            return $result;
        } finally {
            $this->removeTmpFile($tmpFile);
        }
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->eventDispatcher->dispatch(new TmpFileRemoveEvent($tmpFile));

        if ($this->container->hasTmpFile($tmpFile)) {
            $this->container->removeTmpFile($tmpFile);
        }

        if ($this->tmpFileHandler->existsTmpFile($tmpFile)) {
            $this->tmpFileHandler->removeTmpFile($tmpFile);
        }
    }

    public function purge(): void
    {
        $this->eventDispatcher->dispatch(new TmpFileManagerPurgeEvent($this));

        $tmpFiles = $this->container->getTmpFiles();

        foreach ($tmpFiles as $tmpFile) {
            $this->removeTmpFile($tmpFile);
        }
    }

    public function __destruct()
    {
        $this->purge();
    }
}
