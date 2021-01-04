<?php

namespace TmpFileManager\Tests\Listener;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\DeferredPurgeListener;

class DeferredPurgeListenerTest extends TestCase
{
    public function testCallable(): void
    {
        $this->assertIsCallable(new DeferredPurgeListener());
    }
}
