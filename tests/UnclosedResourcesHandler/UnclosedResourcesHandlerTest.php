<?php

namespace TmpFileManager\Tests\UnclosedResourcesHandler;

use TmpFile\TmpFile;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesHandlerTest extends TestCase
{
    public function testCheck()
    {
        $tmpFiles = [];
        $resources = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFile = new TmpFile();

            $tmpFiles[] = $tmpFile;
            $resources[] = fopen($tmpFile, 'r');
        }

        $handler = new UnclosedResourcesHandler();

        $this->assertInstanceOf(UnclosedResourcesHandlerInterface::class, $handler);

        $handler->handle($tmpFiles);

        foreach ($resources as $resource) {
            $this->assertFalse(is_resource($resource));
        }
    }
}