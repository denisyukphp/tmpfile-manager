<?php

namespace TmpFileManager\Tests\Event;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Event\TmpFileRemoveEvent;
use TmpFileManager\TmpFileManager;
use TmpFileManager\TmpFile\TmpFileInterface;

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
