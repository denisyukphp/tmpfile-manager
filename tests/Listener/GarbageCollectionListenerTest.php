<?php

namespace TmpFileManager\Tests\GarbageCollectionHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\GarbageCollectionListener;

class GarbageCollectionListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new GarbageCollectionListener());
    }
}
