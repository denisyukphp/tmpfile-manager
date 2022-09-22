<?php

declare(strict_types=1);

namespace TmpFileManager;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use TmpFile\TmpFileInterface;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\Listener\DeferredPurgeListener;
use TmpFileManager\Listener\GarbageCollectionListener;
use TmpFileManager\Listener\UnclosedResourcesListener;

final class TmpFileManager implements TmpFileManagerInterface
{
    private ConfigInterface $config;
    private ContainerInterface $container;
    private FilesystemInterface $filesystem;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        ?ConfigInterface $config = null,
        ?ContainerInterface $container = null,
        ?FilesystemInterface $filesystem = null,
        ?EventDispatcherInterface $eventDispatcher = null,
    ) {
        $this->config = $config ?? new Config();
        $this->container = $container ?? new Container();
        $this->filesystem = $filesystem ?? new Filesystem();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();

        $this->eventDispatcher->addListener(TmpFileManagerStartEvent::class, new GarbageCollectionListener());
        $this->eventDispatcher->addListener(TmpFileManagerStartEvent::class, new DeferredPurgeListener());
        $this->eventDispatcher->addListener(TmpFileManagerPurgeEvent::class, new UnclosedResourcesListener());
        $this->eventDispatcher->dispatch(new TmpFileManagerStartEvent($this, $this->config, $this->container, $this->filesystem));
    }

    public function create(): TmpFileInterface
    {
        $filename = $this->filesystem->getTmpFileName($this->config->getTmpFileDirectory(), $this->config->getTmpFilePrefix());
        $tmpFile = new TmpFile($filename);
        $this->container->addTmpFile($tmpFile);
        $this->eventDispatcher->dispatch(new TmpFileCreateEvent($tmpFile));

        return $tmpFile;
    }

    public function isolate(callable $callback): void
    {
        $tmpFile = $this->create();

        try {
            $callback($tmpFile);
        } finally {
            $this->remove($tmpFile);
        }
    }

    public function remove(TmpFileInterface $tmpFile): void
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
        $this->eventDispatcher->dispatch(new TmpFileManagerPurgeEvent($this, $this->config, $this->container, $this->filesystem));
        $tmpFiles = $this->container->getTmpFiles();

        foreach ($tmpFiles as $tmpFile) {
            $this->remove($tmpFile);
        }
    }
}
