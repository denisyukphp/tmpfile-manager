<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;

class TmpFileManagerPurgeEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFileManagerPurgeEvent = new TmpFileManagerPurgeEvent($tmpFileManager);

        $this->assertInstanceOf(TmpFileManager::class, $tmpFileManagerPurgeEvent->getTmpFileManager());
    }
}
