<?php

namespace TmpFileManager;

use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\Listener\DeferredPurgeListener;
use TmpFileManager\Listener\GarbageCollectionListener;
use TmpFileManager\Listener\UnclosedResourcesListener;
use TmpFileManager\Provider\ReflectionProvider;
use TmpFileManager\Provider\ProviderInterface;
use TmpFileManager\TmpFile\TmpFileInterface;
use TmpFileManager\Exception\FileNotExistsException;
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
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    /**
     * @var ProviderInterface
     */
    private $provider;

    public function __construct(
        ConfigInterface $config = null,
        ContainerInterface $container = null,
        FilesystemInterface $filesystem = null,
        EventDispatcherInterface $eventDispatcher = null,
        ProviderInterface $provider = null
    ) {
        $this->config = $config ?? Config::createFromDefault();
        $this->container = $container ?? new Container();
        $this->filesystem = $filesystem ?? Filesystem::create();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
        $this->provider = $provider ?? new ReflectionProvider();

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

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->eventDispatcher;
    }

    public function getProvider(): ProviderInterface
    {
        return $this->provider;
    }

    public function createTmpFile(): TmpFileInterface
    {
        $dir = $this->config->getTmpFileDirectory();
        $prefix = $this->config->getTmpFilePrefix();

        $filename = $this->filesystem->getTmpFileName($dir, $prefix);

        return $this->createTmpFileFromFilename($filename);
    }

    private function createTmpFileFromFilename(string $filename): TmpFileInterface
    {
        if (!is_file($filename)) {
            throw new FileNotExistsException(
                sprintf('The file %s not exists', $filename)
            );
        }

        $tmpFile = $this->provider->getTmpFile($filename);

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

        $this->filesystem->copySplFileInfo($splFileInfo, $tmpFile);

        return $tmpFile;
    }

    public function createTmpFileFromUploadedFile(string $filename): TmpFileInterface
    {
        if (!is_uploaded_file($filename)) {
            throw new FileNotUploadedException(
                sprintf('The file %s is not uploaded', $filename)
            );
        }

        return $this->createTmpFileFromFilename($filename);
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

        if ($this->filesystem->existsTmpFile($tmpFile)) {
            $this->filesystem->removeTmpFile($tmpFile);
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
}
