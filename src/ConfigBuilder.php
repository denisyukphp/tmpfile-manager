<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\DeferredPurgeHandler\DefaultDeferredPurgeHandler;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\UnclosedResourcesHandler\DefaultUnclosedResourcesHandler;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\DefaultGarbageCollectionHandler;

class ConfigBuilder
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

    public function __construct()
    {
        $this->tmpFileDirectory = sys_get_temp_dir();
        $this->tmpFilePrefix = 'php';
        $this->deferredAutoPurge = true;
        $this->deferredPurgeHandler = new DefaultDeferredPurgeHandler();
        $this->checkUnclosedResources = false;
        $this->unclosedResourcesHandler = new DefaultUnclosedResourcesHandler();
        $this->garbageCollectionProbability = 0;
        $this->garbageCollectionDivisor = 100;
        $this->garbageCollectionLifetime = 3600;
        $this->garbageCollectionDelay = 0;
        $this->garbageCollectionHandler = new DefaultGarbageCollectionHandler();
    }

    public function setTmpFileDirectory(string $tmpFileDirectory): self
    {
        $this->tmpFileDirectory = $tmpFileDirectory;

        return $this;
    }

    public function getTmpFileDirectory(): string
    {
        return $this->tmpFileDirectory;
    }

    public function setTmpFilePrefix(string $tmpFilePrefix): self
    {
        $this->tmpFilePrefix = $tmpFilePrefix;

        return $this;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }

    public function setDeferredAutoPurge(bool $deferredAutoPurge): self
    {
        $this->deferredAutoPurge = $deferredAutoPurge;

        return $this;
    }

    public function getDeferredAutoPurge(): bool
    {
        return $this->deferredAutoPurge;
    }

    public function setDeferredPurgeHandler(DeferredPurgeHandlerInterface $deferredPurgeHandler): self
    {
        $this->deferredPurgeHandler = $deferredPurgeHandler;

        return $this;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        return $this->deferredPurgeHandler;
    }

    public function setCheckUnclosedResources(bool $checkUnclosedResources): self
    {
        $this->checkUnclosedResources = $checkUnclosedResources;

        return $this;
    }

    public function getCheckUnclosedResources(): bool
    {
        return $this->checkUnclosedResources;
    }

    public function setUnclosedResourcesHandler(UnclosedResourcesHandlerInterface $unclosedResourcesHandler): self
    {
        $this->unclosedResourcesHandler = $unclosedResourcesHandler;

        return $this;
    }

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface
    {
        return $this->unclosedResourcesHandler;
    }

    public function setGarbageCollectionProbability(int $garbageCollectionProbability): self
    {
        $this->garbageCollectionProbability = $garbageCollectionProbability;

        return $this;
    }

    public function getGarbageCollectionProbability(): int
    {
        return $this->garbageCollectionProbability;
    }

    public function setGarbageCollectionDivisor(int $garbageCollectionDivisor): self
    {
        $this->garbageCollectionDivisor = $garbageCollectionDivisor;

        return $this;
    }

    public function getGarbageCollectionDivisor(): int
    {
        return $this->garbageCollectionDivisor;
    }

    public function setGarbageCollectionLifetime(int $garbageCollectionLifetime): self
    {
        $this->garbageCollectionLifetime = $garbageCollectionLifetime;

        return $this;
    }

    public function getGarbageCollectionLifetime(): int
    {
        return $this->garbageCollectionLifetime;
    }

    public function setGarbageCollectionDelay(int $garbageCollectionDelay): self
    {
        $this->garbageCollectionDelay = $garbageCollectionDelay;

        return $this;
    }

    public function getGarbageCollectionDelay(): int
    {
        return $this->garbageCollectionDelay;
    }

    public function setGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $this->garbageCollectionHandler = $garbageCollectionHandler;

        return $this;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        return $this->garbageCollectionHandler;
    }

    public function build(): Config
    {
        return new Config($this);
    }
}