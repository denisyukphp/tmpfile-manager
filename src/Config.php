<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class Config implements ConfigInterface
{
    private $configBuilder;

    public function __construct(ConfigBuilder $configBuilder)
    {
        $this->configBuilder = $configBuilder;
    }

    public function getTmpFileDirectory(): string
    {
        return $this->configBuilder->getTmpFileDirectory();
    }

    public function getTmpFilePrefix(): string
    {
        return $this->configBuilder->getTmpFilePrefix();
    }

    public function getDeferredAutoPurge(): bool
    {
        return $this->configBuilder->getDeferredAutoPurge();
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->configBuilder->getDeferredPurgeHandler();
    }

    public function getCheckUnclosedResources(): bool
    {
        return $this->configBuilder->getCheckUnclosedResources();
    }

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface
    {
        return $this->configBuilder->getUnclosedResourcesHandler();
    }

    public function getGarbageCollectionProbability(): int
    {
        return $this->configBuilder->getGarbageCollectionProbability();
    }

    public function getGarbageCollectionDivisor(): int
    {
        return $this->configBuilder->getGarbageCollectionDivisor();
    }

    public function getGarbageCollectionLifetime(): int
    {
        return $this->configBuilder->getGarbageCollectionLifetime();
    }

    public function getGarbageCollectionDelay(): int
    {
        return $this->configBuilder->getGarbageCollectionDelay();
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->configBuilder->getGarbageCollectionHandler();
    }
}