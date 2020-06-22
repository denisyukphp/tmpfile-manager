<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\RemoveEvent;
use PHPUnit\Framework\TestCase;

class RemoveEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFile = new TmpFile();

        $removeEvent = new RemoveEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $removeEvent->getTmpFile());
    }
}