<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\CreateEvent;
use PHPUnit\Framework\TestCase;

class CreateEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFile = new TmpFile();

        $createEvent = new CreateEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $createEvent->getTmpFile());
    }
}