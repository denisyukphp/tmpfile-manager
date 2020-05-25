<?php

namespace TmpFileManager;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

interface ConfigInterface
{
    public function getTmpFileDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function isDeferredAutoPurge(): bool;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function isCheckUnclosedResources(): bool;

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface;

    public function getGarbageCollectionProbability(): int;

    public function getGarbageCollectionDivisor(): int;

    public function getGarbageCollectionLifetime(): int;

    public function getGarbageCollectionDelay(): int;

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface;
}