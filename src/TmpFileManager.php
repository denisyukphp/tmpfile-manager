<?php

declare(strict_types=1);

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
use TmpFile\TmpFileInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

final class TmpFileManager implements TmpFileManagerInterface
{
    public function __construct(
        private readonly ConfigInterface $config = new Config(),
        private readonly ContainerInterface $container = new Container(),
        private readonly FilesystemInterface $filesystem = new Filesystem(),
        private readonly EventDispatcherInterface $eventDispatcher = new EventDispatcher(),
    ) {
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
