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

    public function __construct(
        ?string $tmpFileDirectory = null,
        private readonly string $tmpFilePrefix = 'php',
        private readonly bool $isDeferredPurge = true,
        private readonly DeferredPurgeHandlerInterface $deferredPurgeHandler = new DeferredPurgeHandler(),
        private readonly bool $isUnclosedResourcesCheck = false,
        private readonly UnclosedResourcesHandlerInterface $unclosedResourcesHandler = new UnclosedResourcesHandler(),
        private readonly int $garbageCollectionProbability = 0,
        private readonly int $garbageCollectionDivisor = 100,
        private readonly int $garbageCollectionLifetime = 3600,
        private readonly GarbageCollectionHandlerInterface $garbageCollectionHandler = new GarbageCollectionHandler(),
    ) {
        $this->tmpFileDirectory = $tmpFileDirectory ?? sys_get_temp_dir();
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
