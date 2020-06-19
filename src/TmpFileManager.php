<?php

namespace Bulletproof\TmpFileManager;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeEvent;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeListener;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionEvent;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionListener;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesEvent;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesListener;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TmpFileManager implements TmpFileManagerInterface
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

        $this->eventDispatcher->dispatch(new DeferredPurgeEvent($this, $this->config));
        $this->eventDispatcher->dispatch(new GarbageCollectionEvent($this->config));
    }

    private function addEventListeners(): void
    {
        $this->eventDispatcher->addListener(DeferredPurgeEvent::class, new DeferredPurgeListener());
        $this->eventDispatcher->addListener(UnclosedResourcesEvent::class, new UnclosedResourcesListener());
        $this->eventDispatcher->addListener(GarbageCollectionEvent::class, new GarbageCollectionListener());
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
     * @throws TmpFileIOException
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

        /** @var TmpFileInterface $tmpFile */
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
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
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

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        if ($this->container->hasTmpFile($tmpFile)) {
            $this->container->removeTmpFile($tmpFile);
        }

        if ($this->tmpFileHandler->existsTmpFile($tmpFile)) {
            $this->tmpFileHandler->removeTmpFile($tmpFile);
        }
    }

    /**
     * @throws TmpFileIOException
     */
    public function purge(): void
    {
        $tmpFilesCount = $this->container->getTmpFilesCount();
        $tmpFiles = $this->container->getTmpFiles();

        if (!$tmpFilesCount) {
            return;
        }

        $this->eventDispatcher->dispatch(new UnclosedResourcesEvent($this->config, $tmpFiles));

        foreach ($tmpFiles as $tmpFile) {
            $this->removeTmpFile($tmpFile);
        }
    }
}