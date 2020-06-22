<?php

namespace TmpFileManager\Tests\UnclosedResourcesHandler;

use TmpFileManager\ConfigBuilder;
use TmpFileManager\TmpFileManager;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesUseCaseTest extends TestCase
{
    public function testSuccess()
    {
        $config = (new ConfigBuilder())
            ->setUnclosedResourcesCheck(true)
            ->build()
        ;

        $tmpFileManager = new TmpFileManager($config);

        $resources = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFile = $tmpFileManager->createTmpFile();

            $resources[] = fopen($tmpFile, 'r');
        }

        $tmpFileManager->purge();

        foreach ($resources as $resource) {
            $this->assertFalse(is_resource($resource));
        }
    }
}