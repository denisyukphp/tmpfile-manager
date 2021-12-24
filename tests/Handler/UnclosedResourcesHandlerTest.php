<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use TmpFileManager\TmpFileManager;
use TmpFileManager\Container\Container;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesHandlerTest extends TestCase
{
    public function testUnclosedResourcesHandler(): void
    {
        $tmpFileManager = new TmpFileManager();

        $container = new Container();

        $tmpFile = $tmpFileManager->create();

        $container->addTmpFile($tmpFile);

        $fh = fopen($tmpFile->getFilename(), 'r');

        $unclosedResourcesHandler = new UnclosedResourcesHandler();

        $unclosedResourcesHandler->handle($container);

        $this->assertIsResource($fh);
    }
}
