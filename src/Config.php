<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\CloseOpenedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class Config implements ConfigInterface
{
    private
        $tmpFileDirectory,
        $tmpFilePrefix,
        $deferredAutoPurge,
        $deferredPurgeHandler,
        $checkUnclosedResources,
        $unclosedResourcesHandler,
        $garbageCollectionProbability,
        $garbageCollectionDivisor,
        $garbageCollectionLifetime,
        $garbageCollectionDelay,
        $garbageCollectionHandler
    ;

    public function __construct(ConfigBuilder $configBuilder)
    {
        $this->tmpFileDirectory = $configBuilder->getTmpFileDirectory();
        $this->tmpFilePrefix = $configBuilder->getTmpFilePrefix();
        $this->deferredAutoPurge = $configBuilder->isDeferredAutoPurge();
        $this->deferredPurgeHandler = $configBuilder->getDeferredPurgeHandler();
        $this->checkUnclosedResources = $configBuilder->isCheckUnclosedResources();
        $this->unclosedResourcesHandler = $configBuilder->getUnclosedResourcesHandler();
        $this->garbageCollectionProbability = $configBuilder->getGarbageCollectionDelay();
        $this->garbageCollectionDivisor = $configBuilder->getGarbageCollectionDivisor();
        $this->garbageCollectionLifetime = $configBuilder->getGarbageCollectionLifetime();
        $this->garbageCollectionDelay = $configBuilder->getGarbageCollectionDelay();
        $this->garbageCollectionHandler = $configBuilder->getGarbageCollectionHandler();
    }

    public function getTmpFileDirectory(): string
    {
        return $this->tmpFileDirectory;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }

    public function isDeferredAutoPurge(): bool
    {
        return $this->deferredAutoPurge;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->deferredPurgeHandler;
    }

    public function isCheckUnclosedResources(): bool
    {
        return $this->checkUnclosedResources;
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

    public function getGarbageCollectionDelay(): int
    {
        return $this->garbageCollectionDelay;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->garbageCollectionHandler;
    }
}