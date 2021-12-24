<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Config;

use TmpFileManager\Config\Config;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    public function testDefaultOptions(): void
    {
        $config = new Config();

        $this->assertSame(sys_get_temp_dir(), $config->getTmpFileDirectory());
        $this->assertSame('php', $config->getTmpFilePrefix());
        $this->assertSame(true, $config->isDeferredPurge());
        $this->assertInstanceOf(DeferredPurgeHandler::class, $config->getDeferredPurgeHandler());
        $this->assertSame(false, $config->isUnclosedResourcesCheck());
        $this->assertInstanceOf(UnclosedResourcesHandler::class, $config->getUnclosedResourcesHandler());
        $this->assertSame(0, $config->getGarbageCollectionProbability());
        $this->assertSame(100, $config->getGarbageCollectionDivisor());
        $this->assertSame(3600, $config->getGarbageCollectionLifetime());
        $this->assertInstanceOf(GarbageCollectionHandler::class, $config->getGarbageCollectionHandler());
    }

    public function testCustomOptions(): void
    {
        $config = new Config(
            tmpFileDirectory: sys_get_temp_dir(),
            tmpFilePrefix: 'php',
            isDeferredPurge: true,
            deferredPurgeHandler: new DeferredPurgeHandler(),
            isUnclosedResourcesCheck: false,
            unclosedResourcesHandler: new UnclosedResourcesHandler(),
            garbageCollectionProbability: 0,
            garbageCollectionDivisor: 100,
            garbageCollectionLifetime: 3600,
            garbageCollectionHandler: new GarbageCollectionHandler(),
        );

        $this->assertSame(sys_get_temp_dir(), $config->getTmpFileDirectory());
        $this->assertSame('php', $config->getTmpFilePrefix());
        $this->assertSame(true, $config->isDeferredPurge());
        $this->assertInstanceOf(DeferredPurgeHandler::class, $config->getDeferredPurgeHandler());
        $this->assertSame(false, $config->isUnclosedResourcesCheck());
        $this->assertInstanceOf(UnclosedResourcesHandler::class, $config->getUnclosedResourcesHandler());
        $this->assertSame(0, $config->getGarbageCollectionProbability());
        $this->assertSame(100, $config->getGarbageCollectionDivisor());
        $this->assertSame(3600, $config->getGarbageCollectionLifetime());
        $this->assertInstanceOf(GarbageCollectionHandler::class, $config->getGarbageCollectionHandler());
    }
}
