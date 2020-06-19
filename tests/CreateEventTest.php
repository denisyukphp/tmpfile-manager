<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\CreateEvent;
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