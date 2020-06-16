<?php

namespace Bulletproof\TmpFileManager\Tests\UnclosedResourcesHandler;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandler;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesHandlerInterface;
use PHPUnit\Framework\TestCase;

class DefaultUnclosedResourcesHandlerTest extends TestCase
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

        $defaultUnclosedResourcesHandler = new UnclosedResourcesHandler();

        $this->assertInstanceOf(UnclosedResourcesHandlerInterface::class, $defaultUnclosedResourcesHandler);

        $defaultUnclosedResourcesHandler($tmpFiles);

        foreach ($resources as $resource) {
            $this->assertFalse(is_resource($resource));
        }
    }
}