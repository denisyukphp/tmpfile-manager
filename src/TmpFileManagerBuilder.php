<?php

declare(strict_types=1);

namespace TmpFileManager;

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use TmpFileManager\Config\Config;
use TmpFileManager\Container\Container;
use TmpFileManager\Event\TmpFileManagerPostPurge;
use TmpFileManager\Event\TmpFileManagerPrePurge;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Handler\OpenResourcesHandler\OpenResourcesHandlerInterface;

final class TmpFileManagerBuilder implements TmpFileManagerBuilderInterface
{
    private SymfonyFilesystem $symfonyFilesystem;
    private EventDispatcherInterface $eventDispatcher;
    private ?string $tmpFileDirectory = null;
    private string $tmpFilePrefix = 'php';

    public function __construct(
        ?SymfonyFilesystem $symfonyFilesystem = null,
        ?EventDispatcherInterface $eventDispatcher = null,
    ) {
        $this->symfonyFilesystem = $symfonyFilesystem ?? new SymfonyFilesystem();
        $this->eventDispatcher = $eventDispatcher ?? new EventDispatcher();
    }

    public function withTmpFileDirectory(string $tmpFileDirectory): self
    {
        $self = clone $this;
        $self->tmpFileDirectory = $tmpFileDirectory;

        return $self;
    }

    public function withTmpFilePrefix(string $tmpFilePrefix): self
    {
        $self = clone $this;
        $self->tmpFilePrefix = $tmpFilePrefix;

        return $self;
    }

    public function withOpenResourcesHandler(OpenResourcesHandlerInterface $openResourcesHandler): self
    {
        $self = clone $this;
        $self->addEventListener(
            TmpFileManagerPrePurge::class,
            static function (TmpFileManagerPrePurge $tmpFileManagerPrePurge) use ($openResourcesHandler): void {
                $openResourcesHandler->handle(
                    tmpFiles: $tmpFileManagerPrePurge->getContainer()->toArray(),
                );
            },
        );

        return $self;
    }

    public function withGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $self = clone $this;
        $self->addEventListener(
            TmpFileManagerPostPurge::class,
            static function (TmpFileManagerPostPurge $tmpFileManagerPostPurge) use ($garbageCollectionHandler): void {
                $garbageCollectionHandler->handle(
                    tmpFileDirectory: $tmpFileManagerPostPurge->getConfig()->getTmpFileDirectory(),
                    tmpFilePrefix: $tmpFileManagerPostPurge->getConfig()->getTmpFilePrefix(),
                );
            },
        );

        return $self;
    }

    public function addEventListener(string $eventName, callable $listenerCallback): self
    {
        $self = clone $this;
        $self->eventDispatcher->addListener($eventName, $listenerCallback);

        return $self;
    }

    public function build(): TmpFileManagerInterface
    {
        return new TmpFileManager(
            config: new Config($this->tmpFileDirectory, $this->tmpFilePrefix),
            container: new Container(),
            filesystem: new Filesystem($this->symfonyFilesystem),
            eventDispatcher: $this->eventDispatcher,
        );
    }
}
