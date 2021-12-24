<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

interface ConfigInterface
{
    public function getTmpFileDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function isDeferredPurge(): bool;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function isUnclosedResourcesCheck(): bool;

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface;

    public function getGarbageCollectionProbability(): int;

    public function getGarbageCollectionDivisor(): int;

    public function getGarbageCollectionLifetime(): int;

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface;
}
