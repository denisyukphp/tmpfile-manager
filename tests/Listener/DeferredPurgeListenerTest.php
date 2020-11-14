<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\DeferredPurgeListener;

class DeferredPurgeListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new DeferredPurgeListener());
    }
}
