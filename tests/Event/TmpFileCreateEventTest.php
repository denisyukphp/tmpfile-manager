<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileCreateEvent;
use TmpFileManager\TmpFileManager;

class TmpFileCreateEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $manager = new TmpFileManager();

        $tmpFile = $manager->createTmpFile();

        $createEvent = new TmpFileCreateEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $createEvent->getTmpFile());
    }
}
