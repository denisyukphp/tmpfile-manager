<?php

namespace Bulletproof\TmpFileManager\Tests\GarbageCollectionHandler;

use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class GarbageCollectionHandlerTest extends TestCase
{
    /**
     * @return TmpFileInterface[]
     */
    private function getTmpFiles(): array
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFiles = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFile = $tmpFileManager->createTmpFile();

            (new Filesystem())->touch($tmpFile, time() - 3600);

            $tmpFiles[] = $tmpFile;
        }

        return $tmpFiles;
    }

    public function testCollect()
    {
        $tmpFiles = $this->getTmpFiles();

        $config = (new ConfigBuilder())
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->build()
        ;

        $defaultGarbageCollectionHandler = new GarbageCollectionHandler();

        $this->assertInstanceOf(GarbageCollectionHandlerInterface::class, $defaultGarbageCollectionHandler);

        $defaultGarbageCollectionHandler($config);

        foreach ($tmpFiles as $tmpFile) {
            $this->assertFileNotExists($tmpFile);
        }
    }
}