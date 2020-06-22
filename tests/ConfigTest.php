<?php

namespace TmpFileManager\Tests;

use TmpFileManager\ConfigBuilder;
use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testTypes()
    {
        $config = (new ConfigBuilder())->build();

        $this->assertIsString($config->getTmpFileDirectory());
        $this->assertIsString($config->getTmpFilePrefix());
        $this->assertIsBool($config->getDeferredPurge());
        $this->assertIsBool($config->getUnclosedResourcesCheck());
        $this->assertIsInt($config->getGarbageCollectionProbability());
        $this->assertIsInt($config->getGarbageCollectionDivisor());
        $this->assertIsInt($config->getGarbageCollectionLifetime());
        $this->assertInstanceOf(DeferredPurgeHandlerInterface::class, $config->getDeferredPurgeHandler());
        $this->assertInstanceOf(UnclosedResourcesHandlerInterface::class, $config->getUnclosedResourcesHandler());
        $this->assertInstanceOf(GarbageCollectionHandlerInterface::class, $config->getGarbageCollectionHandler());
    }
}