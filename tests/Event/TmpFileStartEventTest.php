<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Event\TmpFileManagerStartEvent;
use TmpFileManager\TmpFileManager;

class TmpFileStartEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $manager = new TmpFileManager();

        $startEvent = new TmpFileManagerStartEvent($manager);

        $this->assertInstanceOf(TmpFileManager::class, $startEvent->getTmpFileManager());
    }
}
