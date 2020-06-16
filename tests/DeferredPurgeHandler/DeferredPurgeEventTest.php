<?php

namespace Bulletproof\TmpFileManager\Tests\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\TmpFileManagerInterface;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeEvent;
use PHPUnit\Framework\TestCase;

class DeferredPurgeEventTest extends TestCase
{
    public function testGetters()
    {
        $tmpFileManager = new TmpFileManager();
        $config = (new ConfigBuilder())->build();

        $deferredPurgeEvent = new DeferredPurgeEvent($tmpFileManager, $config);

        $this->assertInstanceOf(TmpFileManagerInterface::class, $deferredPurgeEvent->getTmpFileManager());
        $this->assertInstanceOf(ConfigInterface::class, $deferredPurgeEvent->getConfig());
    }
}