<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;
use TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use PHPUnit\Framework\TestCase;

class DeferredPurgeHandlerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertInstanceOf(DeferredPurgeHandlerInterface::class, new DeferredPurgeHandler());
    }
}