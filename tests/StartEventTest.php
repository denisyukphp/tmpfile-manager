<?php

namespace TmpFileManager\Tests;

use TmpFileManager\TmpFileManager;
use TmpFileManager\StartEvent;
use PHPUnit\Framework\TestCase;

class StartEventTest extends TestCase
{
    public function testSuccess()
    {
        $tmpFileManager = new TmpFileManager();

        $startEvent = new StartEvent($tmpFileManager);

        $this->assertInstanceOf(TmpFileManager::class, $startEvent->getTmpFileManager());
    }
}