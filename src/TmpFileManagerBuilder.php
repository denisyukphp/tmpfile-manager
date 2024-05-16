<?php

declare(strict_types=1);

namespace TmpFileManager;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFileManager\Config\Config;
use TmpFileManager\Container\Container;
use TmpFileManager\Event\TmpFileManagerPostPurge;
use TmpFileManager\Event\TmpFileManagerPrePurge;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

final class TmpFileManagerBuilder implements TmpFileManagerBuilderInterface
{
    private string $tmpFileDir;
    private string $tmpFilePrefix;
    private bool $autoPurge;
    private Fs $fs;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(?Fs $fs = null, ?EventDispatcherInterface $eventDispatcher = null)
    {
        $this->tmpFileDir = sys_get_temp_dir();
        $this->tmpFilePrefix = 'php';
        $this->autoPurge = true;
        $this->fs = $fs ?? new Fs();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
    }

    public function withTmpFileDir(string $tmpFileDir): self
    {
        $self = clone $this;
        $self->tmpFileDir = $tmpFileDir;

        return $self;
    }

    public function withTmpFilePrefix(string $tmpFilePrefix): self
    {
        $self = clone $this;
        $self->tmpFilePrefix = $tmpFilePrefix;

        return $self;
    }

    public function withoutAutoPurge(): self
    {
        $self = clone $this;
        $self->autoPurge = false;

        return $self;
    }

    public function withUnclosedResourcesHandler(UnclosedResourcesHandlerInterface $unclosedResourcesHandler): self
    {
        $self = clone $this;
        $self->withEventListener(
            TmpFileManagerPrePurge::class,
            static function (TmpFileManagerPrePurge $tmpFileManagerPrePurge) use ($unclosedResourcesHandler): void {
                $unclosedResourcesHandler->handle(
                    tmpFiles: $tmpFileManagerPrePurge->getContainer()->getTmpFiles(),
                );
            },
        );

        return $self;
    }

    public function withGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $self = clone $this;
        $self->withEventListener(
            TmpFileManagerPostPurge::class,
            static function (TmpFileManagerPostPurge $tmpFileManagerPostPurge) use ($garbageCollectionHandler): void {
                $garbageCollectionHandler->handle(
                    tmpFileDir: $tmpFileManagerPostPurge->getConfig()->getTmpFileDir(),
                    tmpFilePrefix: $tmpFileManagerPostPurge->getConfig()->getTmpFilePrefix(),
                );
            },
        );

        return $self;
    }

    public function withEventListener(string $eventName, callable $listenerCallback): self
    {
        $self = clone $this;
        $self->eventDispatcher->addListener($eventName, $listenerCallback);

        return $self;
    }

    public function build(): TmpFileManagerInterface
    {
        return new TmpFileManager(
            config: new Config($this->tmpFileDir, $this->tmpFilePrefix),
            container: new Container(),
            filesystem: new Filesystem($this->fs),
            eventDispatcher: $this->eventDispatcher,
            autoPurge: $this->autoPurge,
        );
    }
}
