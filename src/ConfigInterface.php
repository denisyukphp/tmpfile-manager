<?php

namespace Bulletproof\TmpFileManager;

use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

interface ConfigInterface
{
    public function getTmpFileDirectory(): string;

    public function getTmpFilePrefix(): string;

    public function getDeferredAutoPurge(): bool;

    public function getDeferredPurgeHandler(): DeferredPurgeHandlerInterface;

    public function getCheckUnclosedResources(): bool;

    public function getUnclosedResourcesHandler(): UnclosedResourcesHandlerInterface;

    public function getGarbageCollectionProbability(): int;

    public function getGarbageCollectionDivisor(): int;

    public function getGarbageCollectionLifetime(): int;

    public function getGarbageCollectionCallback(): ?callable;

    public function getGarbageCollectionHandler(): GarbageCollectionHandlerInterface;
}