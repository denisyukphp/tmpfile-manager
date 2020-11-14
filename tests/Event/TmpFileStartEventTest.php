<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Event\TmpFileManagerStartEvent;

class TmpFileStartEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFileManagerStartEvent = new TmpFileManagerStartEvent($tmpFileManager);

        $this->assertInstanceOf(TmpFileManager::class, $tmpFileManagerStartEvent->getTmpFileManager());
    }
}
