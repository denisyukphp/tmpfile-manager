<?php

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use Symfony\Component\Filesystem\Filesystem as Fs;

class GarbageCollectionHandlerTest extends TestCase
{
    public function testHandle(): void
    {
        $manager = new TmpFileManager();

        $fs = new Fs();

        $tmpFile = $manager->createTmpFile();

        $fs->touch($tmpFile, time() - 3600);

        $config = ConfigBuilder::create()
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->build()
        ;

        $handler = new GarbageCollectionHandler();

        $handler->handle($config);

        $this->assertFileNotExists($tmpFile);
    }
}
