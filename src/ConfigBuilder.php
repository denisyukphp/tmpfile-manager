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

    public function setTmpFileDirectory(string $tmpFileDirectory): self
    {
        $this->tmpFileDirectory = $tmpFileDirectory;

        return $this;
    }

    public function getTmpFileDirectory(): string
    {
        if (!$this->tmpFileDirectory) {
            $this->tmpFileDirectory = sys_get_temp_dir();
        }

        return $this->tmpFileDirectory;
    }

    public function setTmpFilePrefix(string $tmpFilePrefix): self
    {
        $this->tmpFilePrefix = $tmpFilePrefix;

        return $this;
    }

    public function getTmpFilePrefix(): string
    {
        if (!$this->tmpFilePrefix) {
            $this->tmpFilePrefix = 'php';
        }

        return $this->tmpFilePrefix;
    }

    public function setDeferredAutoPurge(bool $deferredAutoPurge): self
    {
        $this->deferredAutoPurge = $deferredAutoPurge;

        return $this;
    }

    public function isDeferredAutoPurge(): bool
    {
        if (is_null($this->deferredAutoPurge)) {
            $this->deferredAutoPurge = true;
        }

        return $this->deferredAutoPurge;
    }

    public function setDeferredPurgeHandler(DeferredPurgeHandlerInterface $deferredPurgeHandler): self
    {
        $this->deferredPurgeHandler = $deferredPurgeHandler;

        return $this;
    }

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface
    {
        if (!$this->deferredPurgeHandler) {
            $this->deferredPurgeHandler = new DefaultDeferredPurgeHandler();
        }

        return $this->deferredPurgeHandler;
    }

    public function setCheckUnclosedResources(bool $checkUnclosedResources): self
    {
        $this->checkUnclosedResources = $checkUnclosedResources;

        return $this;
    }

    public function isCheckUnclosedResources(): bool
    {
        if (is_null($this->checkUnclosedResources)) {
            $this->checkUnclosedResources = false;
        }

        return $this->checkUnclosedResources;
    }

    public function setUnclosedResourcesHandler(UnclosedResourcesHandlerInterface $unclosedResourcesHandler): self
    {
        $this->unclosedResourcesHandler = $unclosedResourcesHandler;

        return $this;
    }

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface
    {
        if (!$this->unclosedResourcesHandler) {
            $this->unclosedResourcesHandler = new DefaultUnclosedResourcesHandler();
        }

        return $this->unclosedResourcesHandler;
    }

    public function setGarbageCollectionProbability(int $garbageCollectionProbability): self
    {
        $this->garbageCollectionProbability = $garbageCollectionProbability;

        return $this;
    }

    public function getGarbageCollectionProbability(): int
    {
        if (is_null($this->garbageCollectionProbability)) {
            $this->garbageCollectionProbability = 0;
        }

        return $this->garbageCollectionProbability;
    }

    public function setGarbageCollectionDivisor(int $garbageCollectionDivisor): self
    {
        $this->garbageCollectionDivisor = $garbageCollectionDivisor;

        return $this;
    }

    public function getGarbageCollectionDivisor(): int
    {
        if (is_null($this->garbageCollectionDivisor)) {
            $this->garbageCollectionDivisor = 100;
        }

        return $this->garbageCollectionDivisor;
    }

    public function setGarbageCollectionLifetime(int $garbageCollectionLifetime): self
    {
        $this->garbageCollectionLifetime = $garbageCollectionLifetime;

        return $this;
    }

    public function getGarbageCollectionLifetime(): int
    {
        if (is_null($this->garbageCollectionLifetime)) {
            $this->garbageCollectionLifetime = 3600;
        }

        return $this->garbageCollectionLifetime;
    }

    public function setGarbageCollectionDelay(int $garbageCollectionDelay): self
    {
        $this->garbageCollectionDelay = $garbageCollectionDelay;

        return $this;
    }

    public function getGarbageCollectionDelay(): int
    {
        if (is_null($this->garbageCollectionDelay)) {
            $this->garbageCollectionDelay = 0;
        }

        return $this->garbageCollectionDelay;
    }

    public function setGarbageCollectionHandler(GarbageCollectionHandlerInterface $garbageCollectionHandler): self
    {
        $this->garbageCollectionHandler = $garbageCollectionHandler;

        return $this;
    }

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface
    {
        if (!$this->garbageCollectionHandler) {
            $this->garbageCollectionHandler = new DefaultGarbageCollectionHandler();
        }

        return $this->garbageCollectionHandler;
    }

    public function build(): Config
    {
        return new Config($this);
    }
}