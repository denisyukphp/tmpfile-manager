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
    public function testSetters()
    {
        $configBuilder = new ConfigBuilder();

        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setTmpFileDirectory(sys_get_temp_dir()));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setTmpFilePrefix('php'));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setDeferredPurge(true));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setDeferredPurgeHandler(new DeferredPurgeHandler()));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setUnclosedResourcesCheck(false));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setUnclosedResourcesHandler(new UnclosedResourcesHandler()));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionProbability(0));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionDivisor(100));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionLifetime(3600));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionHandler(new GarbageCollectionHandler()));

        return $configBuilder;
    }

    /**
     * @param ConfigBuilder $configBuilder
     *
     * @depends testSetters
     */
    public function testGetters(ConfigBuilder $configBuilder)
    {
        $this->assertEquals(sys_get_temp_dir(), $configBuilder->getTmpFileDirectory());
        $this->assertEquals('php', $configBuilder->getTmpFilePrefix());
        $this->assertEquals(true, $configBuilder->getDeferredPurge());
        $this->assertEquals(new DeferredPurgeHandler(), $configBuilder->getDeferredPurgeHandler());
        $this->assertEquals(false, $configBuilder->getUnclosedResourcesCheck());
        $this->assertEquals(new UnclosedResourcesHandler(), $configBuilder->getUnclosedResourcesHandler());
        $this->assertEquals(0, $configBuilder->getGarbageCollectionProbability());
        $this->assertEquals(100, $configBuilder->getGarbageCollectionDivisor());
        $this->assertEquals(3600, $configBuilder->getGarbageCollectionLifetime());
        $this->assertEquals(new GarbageCollectionHandler(), $configBuilder->getGarbageCollectionHandler());
    }

    /**
     * @param ConfigBuilder $configBuilder
     *
     * @depends testSetters
     */
    public function testBuild(ConfigBuilder $configBuilder)
    {
        $this->assertInstanceOf(ConfigInterface::class, $configBuilder->build());
    }
}
