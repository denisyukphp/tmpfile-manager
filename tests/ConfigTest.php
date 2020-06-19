<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
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