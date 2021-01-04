<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;

class TmpFileManagerPurgeEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFileManagerPurgeEvent = new TmpFileManagerPurgeEvent($tmpFileManager);

        $this->assertInstanceOf(TmpFileManager::class, $tmpFileManagerPurgeEvent->getTmpFileManager());
    }
}
