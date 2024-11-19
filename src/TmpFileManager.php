<?php

declare(strict_types=1);

namespace TmpFileManager;

use Psr\EventDispatcher\EventDispatcherInterface;
use TmpFile\TmpFileInterface;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Event\TmpFileManagerEventArgs;
use TmpFileManager\Event\TmpFileManagerOnFinish;
use TmpFileManager\Event\TmpFileManagerOnStart;
use TmpFileManager\Event\TmpFileManagerPostCreate;
use TmpFileManager\Event\TmpFileManagerPostLoad;
use TmpFileManager\Event\TmpFileManagerPostPurge;
use TmpFileManager\Event\TmpFileManagerPreCreate;
use TmpFileManager\Event\TmpFileManagerPreLoad;
use TmpFileManager\Event\TmpFileManagerPrePurge;
use TmpFileManager\Event\TmpFileOnCreate;
use TmpFileManager\Event\TmpFileOnLoad;
use TmpFileManager\Event\TmpFilePostRemove;
use TmpFileManager\Event\TmpFilePreRemove;
use TmpFileManager\Filesystem\FilesystemInterface;

final class TmpFileManager implements TmpFileManagerInterface
{
    public function __construct(
        private ConfigInterface $config,
        private ContainerInterface $container,
        private FilesystemInterface $filesystem,
        private EventDispatcherInterface $eventDispatcher,
        private bool $autoPurge = true,
    ) {
        $this->eventDispatcher->dispatch(new TmpFileManagerOnStart($this->getTmpFileManagerEventArgs()));

        if ($this->autoPurge) {
            register_shutdown_function([$this, 'purge']);
        }
    }

    private function getTmpFileManagerEventArgs(): TmpFileManagerEventArgs
    {
        return new TmpFileManagerEventArgs($this->config, $this->container, $this->filesystem);
    }

    public function create(): TmpFileInterface
    {
        $this->eventDispatcher->dispatch(new TmpFileManagerPreCreate($this->getTmpFileManagerEventArgs()));
        $tmpFile = $this->filesystem->createTmpFile($this->config->getTmpFileDir(), $this->config->getTmpFilePrefix());
        $this->container->addTmpFile($tmpFile);
        $this->eventDispatcher->dispatch(new TmpFileOnCreate($tmpFile));
        $this->eventDispatcher->dispatch(new TmpFileManagerPostCreate($this->getTmpFileManagerEventArgs()));

        return $tmpFile;
    }

    public function load(TmpFileInterface ...$tmpFiles): void
    {
        $this->eventDispatcher->dispatch(new TmpFileManagerPreLoad($this->getTmpFileManagerEventArgs()));

        foreach ($tmpFiles as $tmpFile) {
            if (!$this->filesystem->existsTmpFile($tmpFile)) {
                throw new \InvalidArgumentException(\sprintf('Temp file "%s" doesn\'t exist.', $tmpFile->getFilename()));
            }

            $this->container->addTmpFile($tmpFile);
            $this->eventDispatcher->dispatch(new TmpFileOnLoad($tmpFile));
        }

        $this->eventDispatcher->dispatch(new TmpFileManagerPostLoad($this->getTmpFileManagerEventArgs()));
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
        if (!$this->filesystem->existsTmpFile($tmpFile)) {
            throw new \InvalidArgumentException(\sprintf('Temp file "%s" has been already removed.', $tmpFile->getFilename()));
        }

        if (!$this->container->hasTmpFile($tmpFile)) {
            throw new \InvalidArgumentException(\sprintf('Temp file "%s" wasn\'t create through temp file manager.', $tmpFile->getFilename()));
        }

        $this->eventDispatcher->dispatch(new TmpFilePreRemove($tmpFile));
        $this->container->removeTmpFile($tmpFile);
        $this->filesystem->removeTmpFile($tmpFile);
        $this->eventDispatcher->dispatch(new TmpFilePostRemove($tmpFile));
    }

    public function purge(): void
    {
        $this->eventDispatcher->dispatch(new TmpFileManagerPrePurge($this->getTmpFileManagerEventArgs()));

        if (!$this->container->isEmpty()) {
            foreach ($this->container->getTmpFiles() as $tmpFile) {
                $this->remove($tmpFile);
            }
        }

        $this->eventDispatcher->dispatch(new TmpFileManagerPostPurge($this->getTmpFileManagerEventArgs()));
    }

    public function __destruct()
    {
        $this->eventDispatcher->dispatch(new TmpFileManagerOnFinish($this->getTmpFileManagerEventArgs()));
    }
}
