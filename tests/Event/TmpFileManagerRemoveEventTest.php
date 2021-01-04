<?php

namespace TmpFileManager\Tests\Event;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileRemoveEvent;

class TmpFileManagerRemoveEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $tmpFile = new TmpFile();

        $tmpFileRemoveEvent = new TmpFileRemoveEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFileRemoveEvent->getTmpFile());
    }
}
