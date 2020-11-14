<?php

namespace TmpFileManager\Config;

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

interface ConfigInterface
{
    public function getTmpFileDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function getDeferredPurge(): bool;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function getUnclosedResourcesCheck(): bool;

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface;

    public function getGarbageCollectionProbability(): int;

    public function getGarbageCollectionDivisor(): int;

    public function getGarbageCollectionLifetime(): int;

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface;
}
