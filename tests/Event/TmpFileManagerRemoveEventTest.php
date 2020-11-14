<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileRemoveEvent;

class TmpFileManagerRemoveEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFile = new TmpFile();

        $tmpFileRemoveEvent = new TmpFileRemoveEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFileRemoveEvent->getTmpFile());
    }
}
