<?php

namespace TmpFileManager\Tests\UnclosedResourcesHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Listener\UnclosedResourcesListener;

class UnclosedResourcesListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new UnclosedResourcesListener());
    }
}
