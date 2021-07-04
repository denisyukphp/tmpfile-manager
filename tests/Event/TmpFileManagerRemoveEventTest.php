<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\TmpFileManager;

class TmpFileManagerRemoveEventTest extends TestCase
{
    public function testSuccess(): void
    {
        $manager = new TmpFileManager();

        $tmpFile = $manager->createTmpFile();

        $removeEvent = new TmpFileRemoveEvent($tmpFile);

        $this->assertInstanceOf(TmpFileInterface::class, $removeEvent->getTmpFile());
    }
}
