<?php

namespace TmpFileManager\Tests\UnclosedResourcesHandler;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;

class UnclosedResourcesHandlerTest extends TestCase
{
    public function testHandle()
    {
        $tmpFiles = [];

        $resources = [];

        for ($i = 0; $i < 5; $i++) {
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
