<?php

namespace TmpFileManager;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandler;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use TmpFileManager\TmpFileReflection\TmpFileReflection;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\Listener\DeferredPurgeListener;
use TmpFileManager\Listener\GarbageCollectionListener;
use TmpFileManager\Listener\UnclosedResourcesListener;
use TmpFileManager\Exception\FileNotUploadedException;
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
        $this->config = $config ?? Config::createFromDefault();
        $this->container = $container ?? new Container();
        $this->tmpFileHandler = $tmpFileHandler ?? TmpFileHandler::create();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();

        $this->addDefaultListeners();

        $this->eventDispatcher->dispatch(new TmpFileManagerStartEvent($this));
    }

    private function addDefaultListeners(): void
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

    public function createTmpFile(): TmpFileInterface
    {
        $dir = $this->config->getTmpFileDirectory();
        $prefix = $this->config->getTmpFilePrefix();

        $filename = $this->tmpFileHandler->getTmpFileName($dir, $prefix);

        return $this->getTmpFile($filename);
    }

    private function getTmpFile(string $realPath): TmpFileInterface
    {
        $tmpFileReflection = new TmpFileReflection(TmpFile::class);

        $tmpFile = $tmpFileReflection->changeFilename($realPath);

        $this->container->addTmpFile($tmpFile);

        $this->eventDispatcher->dispatch(new TmpFileCreateEvent($tmpFile));

        return $tmpFile;
    }

    public function createTmpFileContext(callable $callback): void
    {
        $tmpFile = $this->createTmpFile();

        try {
            $callback($tmpFile);
        } finally {
            $this->removeTmpFile($tmpFile);
        }
    }

    public function createTmpFileFromSplFileInfo(\SplFileInfo $splFileInfo): TmpFileInterface
    {
        $tmpFile = $this->createTmpFile();

        $this->tmpFileHandler->copySplFileInfo($splFileInfo, $tmpFile);

        return $tmpFile;
    }

    public function createTmpFileFromUploadedFile(string $filename): TmpFileInterface
    {
        if (!is_uploaded_file($filename)) {
            throw new FileNotUploadedException(
                sprintf('The file %s is not uploaded', $filename)
            );
        }

        return $this->getTmpFile($filename);
    }

    public function copyTmpFile(TmpFileInterface $tmpFile): TmpFileInterface
    {
        $splFileInfo = new \SplFileInfo($tmpFile);

        return $this->createTmpFileFromSplFileInfo($splFileInfo);
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
