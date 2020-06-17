<?php

namespace Bulletproof\TmpFileManager\Tests\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandler;
use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeHandlerInterface;
use PHPUnit\Framework\TestCase;

class DeferredPurgeHandlerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertInstanceOf(DeferredPurgeHandlerInterface::class, new DeferredPurgeHandler());
    }
}