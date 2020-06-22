<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use TmpFileManager\DeferredPurgeHandler\DeferredPurgeListener;
use PHPUnit\Framework\TestCase;

class DeferredPurgeListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new DeferredPurgeListener());
    }
}