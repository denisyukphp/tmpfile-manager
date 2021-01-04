<?php

namespace TmpFileManager\Tests\Event;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileCreateEvent;

class TmpFileCreateEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $tmpFile = new TmpFile();

        $tmpFileCreateEvent = new TmpFileCreateEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFileCreateEvent->getTmpFile());
    }
}
