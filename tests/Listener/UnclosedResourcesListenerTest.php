<?php

namespace TmpFileManager\Tests\Listener;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\UnclosedResourcesListener;

class UnclosedResourcesListenerTest extends TestCase
{
    public function testCallable(): void
    {
        $this->assertIsCallable(new UnclosedResourcesListener());
    }
}
