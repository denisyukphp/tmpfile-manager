<?php

use TmpFile\TmpFile;
use TmpFileManager\TmpFileManager;
use TmpFileManager\ConfigBuilder;
use PHPUnit\Framework\TestCase;

class TmpFileManagerGarbageCollectionTest extends TestCase
{
    protected $tmpFilePrefix = 'test';

    /** @var TmpFile[] */
    protected $tmpFiles = [];

    public function setUp()
    {
        $config = (new ConfigBuilder())
            ->setTmpFilePrefix($this->tmpFilePrefix)
            ->build()
        ;

        $tmpFileManager = new TmpFileManager($config);

        for ($i = 0; $i < 10; $i++) {
            $this->tmpFiles[] = $tmpFileManager->createTmpFile();
        }
    }

    public function testGarbageCollection()
    {
        $config = (new ConfigBuilder())
            ->setTmpFilePrefix($this->tmpFilePrefix)
            ->setGarbageCollectionProbability(100)
            ->setGarbageCollectionLifetime(0)
            ->setGarbageCollectionDelay(1)
            ->build()
        ;

        new TmpFileManager($config);

        foreach ($this->tmpFiles as $tmpFile) {
            $this->assertFileNotExists($tmpFile);
        }
    }
}