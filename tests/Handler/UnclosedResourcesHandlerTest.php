<?php

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Container\Container;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;

class UnclosedResourcesHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $tmpFile = $manager->createTmpFile();

        $container->addTmpFile($tmpFile);

        $fh = fopen($tmpFile, 'r');

        $handler = new UnclosedResourcesHandler();

        $handler->handle($container);

        $this->assertFalse(is_resource($fh));
    }
}
