<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Container\Container;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\TmpFileManager;

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

        $this->assertFalse(\is_resource($fh));
    }
}
