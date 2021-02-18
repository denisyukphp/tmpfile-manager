<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Event\TmpFileManagerPurgeEvent;
use TmpFileManager\TmpFileManager;

class TmpFileManagerPurgeEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $manager = new TmpFileManager();

        $purgeEvent = new TmpFileManagerPurgeEvent($manager);

        $this->assertInstanceOf(TmpFileManager::class, $purgeEvent->getTmpFileManager());
    }
}
