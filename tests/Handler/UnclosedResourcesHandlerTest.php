<?php

namespace TmpFileManager\Tests\Handler;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFileManager\Container\Container;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;

class UnclosedResourcesHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $tmpFile = new TmpFile();

        $container = new Container();

        $container->addTmpFile($tmpFile);

        $fh = fopen($tmpFile, 'r');

        $handler = new UnclosedResourcesHandler();

        $handler->handle($container);

        $this->assertFalse(is_resource($fh));
    }
}
