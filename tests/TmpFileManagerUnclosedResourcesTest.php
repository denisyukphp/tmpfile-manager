<?php

use TmpFileManager\TmpFileManager;
use TmpFileManager\ConfigBuilder;
use PHPUnit\Framework\TestCase;

class TmpFileManagerUnclosedResourcesTest extends TestCase
{
    /** @var TmpFileManager */
    protected $tmpFileManager;

    public function setUp()
    {
        $config = (new ConfigBuilder())
            ->setTmpFilePrefix('test')
            ->setCheckUnclosedResources(true)
            ->build()
        ;

        $this->tmpFileManager = new TmpFileManager($config);
    }

    /**
     * @throws \Exception
     */
    public function testUnclosedResources()
    {
        $resources = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFile = $this->tmpFileManager->createTmpFile();

            $resources[] = fopen($tmpFile, 'r+');
        }

        $this->tmpFileManager->purge();

        foreach ($resources as $fh) {
            $this->assertFalse(is_resource($fh));
        }
    }
}