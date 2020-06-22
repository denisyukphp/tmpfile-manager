<?php

namespace TmpFileManager\Tests;

use TmpFileManager\TmpFileManager;
use TmpFileManager\PurgeEvent;
use PHPUnit\Framework\TestCase;

class PurgeEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFileManager = new TmpFileManager();

        $purgeEvent = new PurgeEvent($tmpFileManager);

        $this->assertInstanceOf(TmpFileManager::class, $purgeEvent->getTmpFileManager());
    }
}