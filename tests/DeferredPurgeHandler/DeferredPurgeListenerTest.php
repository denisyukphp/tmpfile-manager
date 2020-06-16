<?php

namespace Bulletproof\TmpFileManager\Tests\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\DeferredPurgeHandler\DeferredPurgeListener;
use PHPUnit\Framework\TestCase;

class DeferredPurgeListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new DeferredPurgeListener());
    }
}