<?php

namespace Bulletproof\TmpFileManager\Tests\GarbageCollectionHandler;

use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionListener;
use PHPUnit\Framework\TestCase;

class GarbageCollectionListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new GarbageCollectionListener());
    }
}