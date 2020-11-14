<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeHandlerInterface;

class DeferredPurgeHandlerTest extends TestCase
{
    public function testInstance()
    {
        $this->assertInstanceOf(DeferredPurgeHandlerInterface::class, new DeferredPurgeHandler());
    }
}
