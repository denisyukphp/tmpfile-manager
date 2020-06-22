<?php

namespace TmpFileManager\Tests\GarbageCollectionHandler;

use TmpFileManager\GarbageCollectionHandler\GarbageCollectionListener;
use PHPUnit\Framework\TestCase;

class GarbageCollectionListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new GarbageCollectionListener());
    }
}