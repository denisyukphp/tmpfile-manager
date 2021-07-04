<?php

namespace TmpFileManager\Tests\Config;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;

class ConfigBuilderTest extends TestCase
{
    public function testCreate(): void
    {
        $builder = ConfigBuilder::create();

        $this->assertInstanceOf(ConfigBuilder::class, $builder);
    }

    public function testTmpFileDirectory(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setTmpFileDirectory(sys_get_temp_dir());

        $this->assertEquals(sys_get_temp_dir(), $builder->getTmpFileDirectory());
    }

    public function testTmpFilePrefix(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setTmpFilePrefix('php');

        $this->assertEquals('php', $builder->getTmpFilePrefix());
    }

    public function testDeferredPurge(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setDeferredPurge(true);

        $this->assertTrue($builder->getDeferredPurge());
    }

    public function testDeferredPurgeHandler(): void
    {
        $builder = ConfigBuilder::create();

        $handler = new DeferredPurgeHandler();

        $builder->setDeferredPurgeHandler($handler);

        $this->assertSame($handler, $builder->getDeferredPurgeHandler());
    }

    public function testUnclosedResourcesCheck(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setUnclosedResourcesCheck(false);

        $this->assertFalse($builder->getUnclosedResourcesCheck());
    }

    public function testUnclosedResourcesHandler(): void
    {
        $builder = ConfigBuilder::create();

        $handler = new UnclosedResourcesHandler();

        $builder->setUnclosedResourcesHandler($handler);

        $this->assertSame($handler, $builder->getUnclosedResourcesHandler());
    }

    public function testGarbageCollectionProbability(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setGarbageCollectionProbability(0);

        $this->assertSame(0, $builder->getGarbageCollectionProbability());
    }

    public function testGarbageCollectionDivisor(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setGarbageCollectionDivisor(100);

        $this->assertSame(100, $builder->getGarbageCollectionDivisor());
    }

    public function testGarbageCollectionLifetime(): void
    {
        $builder = ConfigBuilder::create();

        $builder->setGarbageCollectionLifetime(3600);

        $this->assertSame(3600, $builder->getGarbageCollectionLifetime());
    }

    public function testGarbageCollectionHandler(): void
    {
        $builder = ConfigBuilder::create();

        $handler = new GarbageCollectionHandler();

        $builder->setGarbageCollectionHandler($handler);

        $this->assertSame($handler, $builder->getGarbageCollectionHandler());
    }

    public function testBuild(): void
    {
        $builder = ConfigBuilder::create()->build();

        $this->assertInstanceOf(ConfigInterface::class, $builder);
    }
}
