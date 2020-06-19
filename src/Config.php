<?php

namespace Bulletproof\TmpFileManager;

use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

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

    public function getDeferredPurge(): bool
    {
        return $this->configBuilder->getDeferredPurge();
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->configBuilder->getDeferredPurgeHandler();
    }

    public function getUnclosedResourcesCheck(): bool
    {
        return $this->configBuilder->getUnclosedResourcesCheck();
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

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->configBuilder->getGarbageCollectionHandler();
    }
}