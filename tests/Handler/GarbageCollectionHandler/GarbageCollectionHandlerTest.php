<?php

namespace TmpFileManager\Tests\GarbageCollectionHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\ConfigBuilder;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;

class GarbageCollectionHandlerTest extends TestCase
{
    public function testHandle()
    {
        $tmpFileManager = new TmpFileManager();

        $filesystem = new Filesystem();

        $tmpFiles = [];

        for ($i = 0; $i < 5; $i++) {
            $tmpFile = $tmpFileManager->createTmpFile();

            $filesystem->touch($tmpFile, time() - 3600);

            $tmpFiles[] = $tmpFile;
        }

        $config = (new ConfigBuilder())
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->build()
        ;

        $handler = new GarbageCollectionHandler();

        $this->assertInstanceOf(GarbageCollectionHandlerInterface::class, $handler);

        $handler->handle($config);

        foreach ($tmpFiles as $tmpFile) {
            $this->assertFileNotExists($tmpFile);
        }
    }
}
