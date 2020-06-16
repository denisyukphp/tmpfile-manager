<?php

namespace Bulletproof\TmpFileManager\Tests\GarbageCollectionHandler;

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionEvent;
use PHPUnit\Framework\TestCase;

class GarbageCollectionEventTest extends TestCase
{
    public function testGetters()
    {
        $config = (new ConfigBuilder())->build();

        $garbageCollectionEvent = new GarbageCollectionEvent($config);

        $this->assertInstanceOf(ConfigInterface::class, $garbageCollectionEvent->getConfig());
    }
}