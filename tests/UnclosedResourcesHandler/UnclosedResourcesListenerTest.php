<?php

namespace Bulletproof\TmpFileManager\Tests\UnclosedResourcesHandler;

use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesListener;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesListenerTest extends TestCase
{
    public function testCallable()
    {
        $this->assertIsCallable(new UnclosedResourcesListener());
    }
}