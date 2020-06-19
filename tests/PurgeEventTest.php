<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\PurgeEvent;
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