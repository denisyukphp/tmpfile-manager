<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

final class Config implements ConfigInterface
{
    public function __construct(
        private ?string $tmpFileDirectory = null,
        private string $tmpFilePrefix = 'php',
        private bool $isDeferredPurge = true,
        private DeferredPurgeHandlerInterface $deferredPurgeHandler = new DeferredPurgeHandler(),
        private bool $isUnclosedResourcesCheck = false,
        private UnclosedResourcesHandlerInterface $unclosedResourcesHandler = new UnclosedResourcesHandler(),
        private int $garbageCollectionProbability = 0,
        private int $garbageCollectionDivisor = 100,
        private int $garbageCollectionLifetime = 3600,
        private GarbageCollectionHandlerInterface $garbageCollectionHandler = new GarbageCollectionHandler(),
    ) {
        $this->tmpFileDirectory ??= sys_get_temp_dir();
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

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->garbageCollectionHandler;
    }
}
