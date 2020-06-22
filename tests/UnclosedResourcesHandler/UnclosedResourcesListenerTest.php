<?php

namespace TmpFileManager\Tests\UnclosedResourcesHandler;

use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesListener;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new UnclosedResourcesListener());
    }
}