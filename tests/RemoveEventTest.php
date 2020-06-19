<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\RemoveEvent;
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