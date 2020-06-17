<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;
use PHPUnit\Framework\TestCase;

class ConfigBuilderTest extends TestCase
{
    /**
     * @var callable
     */
    private $garbageCollectionCallback;

    public function setUp()
    {
        $this->garbageCollectionCallback = function () {
            return true;
        };
    }

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
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionCallback($this->garbageCollectionCallback));
        $this->assertInstanceOf(ConfigBuilder::class, $configBuilder->setGarbageCollectionHandler(new GarbageCollectionHandler()));

        return $configBuilder;
    }

    /**
     * @depends testSetters
     *
     * @param ConfigBuilder $configBuilder
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
        $this->assertEquals($this->garbageCollectionCallback, $configBuilder->getGarbageCollectionCallback());
        $this->assertEquals(new GarbageCollectionHandler(), $configBuilder->getGarbageCollectionHandler());
    }

    /**
     * @depends testSetters
     *
     * @param ConfigBuilder $configBuilder
     */
    public function testBuild(ConfigBuilder $configBuilder)
    {
        $this->assertInstanceOf(ConfigInterface::class, $configBuilder->build());
    }
}