<?php

namespace Bulletproof\TmpFileManager\Tests\GarbageCollectionHandler;

use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandler;
use Bulletproof\TmpFileManager\GarbageCollectionHandler\GarbageCollectionHandlerInterface;
use PHPUnit\Framework\TestCase;

class DefaultGarbageCollectionHandlerTest extends TestCase
{
    private function getConfigBuilder(): ConfigBuilder
    {
        return (new ConfigBuilder())
            ->setTmpFileDirectory(sys_get_temp_dir())
            ->setTmpFilePrefix('php')
        ;
    }

    /**
     * @return TmpFileInterface[]
     */
    private function getTmpFiles(): array
    {
        $tmpFileManager = new TmpFileManager($this->getConfigBuilder()->build());

        $tmpFiles = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFiles[] = $tmpFileManager->createTmpFile();
        }

        return $tmpFiles;
    }

    public function testCollect()
    {
        $tmpFiles = $this->getTmpFiles();

        $config = $this->getConfigBuilder()
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->setGarbageCollectionCallback(function () use ($tmpFiles) {
                foreach ($tmpFiles as $tmpFile) {
                    $this->assertFileNotExists($tmpFile);
                }
            })
            ->build()
        ;

        $defaultGarbageCollectionHandler = new GarbageCollectionHandler();

        $this->assertInstanceOf(GarbageCollectionHandlerInterface::class, $defaultGarbageCollectionHandler);

        $defaultGarbageCollectionHandler($config);
    }
}