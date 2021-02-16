<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Config\Config;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;

class ConfigTest extends TestCase
{
    public function testCreateFromDefault(): void
    {
        $config = Config::createFromDefault();

        $this->assertInstanceOf(ConfigInterface::class, $config);
    }

    public function testTypes(): void
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
