<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileCreateEvent;

class TmpFileCreateEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFile = new TmpFile();

        $tmpFileCreateEvent = new TmpFileCreateEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFileCreateEvent->getTmpFile());
    }
}
