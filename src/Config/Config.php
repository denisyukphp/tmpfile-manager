<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

final class Config implements ConfigInterface
{
    private string $tmpFileDirectory;
    private DeferredPurgeHandlerInterface $deferredPurgeHandler;
    private UnclosedResourcesHandlerInterface $unclosedResourcesHandler;
    private GarbageCollectionHandlerInterface $garbageCollectionHandler;

    public function __construct(
        ?string $tmpFileDirectory = null,
        private string $tmpFilePrefix = 'php',
        private bool $isDeferredPurge = true,
        ?DeferredPurgeHandlerInterface $deferredPurgeHandler = null,
        private bool $isUnclosedResourcesCheck = false,
        ?UnclosedResourcesHandlerInterface $unclosedResourcesHandler = null,
        private int $garbageCollectionProbability = 0,
        private int $garbageCollectionDivisor = 100,
        private int $garbageCollectionLifetime = 3600,
        ?GarbageCollectionHandlerInterface $garbageCollectionHandler = null,
    ) {
        $this->tmpFileDirectory = $tmpFileDirectory ?? sys_get_temp_dir();
        $this->deferredPurgeHandler = $deferredPurgeHandler ?? new DeferredPurgeHandler();
        $this->unclosedResourcesHandler = $unclosedResourcesHandler ?? new UnclosedResourcesHandler();
        $this->garbageCollectionHandler = $garbageCollectionHandler ?? new GarbageCollectionHandler();
    }

    public function getTmpFileDirectory(): string
    {
        return $this->tmpFileDirectory;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }

    public function isDeferredPurge(): bool
    {
        return $this->isDeferredPurge;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->deferredPurgeHandler;
    }

    public function isUnclosedResourcesCheck(): bool
    {
        return $this->isUnclosedResourcesCheck;
    }

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface
    {
        return $this->unclosedResourcesHandler;
    }

    public function getGarbageCollectionProbability(): int
    {
        return $this->garbageCollectionProbability;
    }

    public function getGarbageCollectionDivisor(): int
    {
        return $this->garbageCollectionDivisor;
    }

    public function getGarbageCollectionLifetime(): int
    {
        return $this->garbageCollectionLifetime;
    }

    public function isGarbageCollection(): bool
    {
        return 0 < $this->garbageCollectionProbability;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->garbageCollectionHandler;
    }
}
