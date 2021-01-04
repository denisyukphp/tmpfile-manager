<?php

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use Symfony\Component\Filesystem\Filesystem;

class GarbageCollectionHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $tmpFileManager = new TmpFileManager();

        $filesystem = new Filesystem();

        $tmpFile = $tmpFileManager->createTmpFile();

        $filesystem->touch($tmpFile, time() - 3600);

        $config = (new ConfigBuilder())
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->build()
        ;

        $handler = new GarbageCollectionHandler();

        $handler->handle($config);

        $this->assertFileNotExists($tmpFile);
    }
}
