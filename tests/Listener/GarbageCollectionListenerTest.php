<?php

namespace TmpFileManager\Tests\Listener;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\GarbageCollectionListener;

class GarbageCollectionListenerTest extends TestCase
{
    public function testCallable(): void
    {
        $this->assertIsCallable(new GarbageCollectionListener());
    }
}
