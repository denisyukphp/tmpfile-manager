<?php

namespace Bulletproof\TmpFileManager\Tests\UnclosedResourcesHandler;

use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFileManager\ConfigBuilder;
use Bulletproof\TmpFileManager\ConfigInterface;
use Bulletproof\TmpFileManager\UnclosedResourcesHandler\UnclosedResourcesEvent;
use PHPUnit\Framework\TestCase;

class UnclosedResourcesEventTest extends TestCase
{
    /**
     * @return TmpFileInterface[]
     */
    private function getTmpFiles(): array
    {
        $tmpFiles = [];

        for ($i = 0; $i < 10; $i++) {
            $tmpFiles[] = new TmpFile();
        }

        return $tmpFiles;
    }

    public function testGetters()
    {
        $config = (new ConfigBuilder())->build();
        $tmpFiles = $this->getTmpFiles();

        $unclosedResourcesEvent = new UnclosedResourcesEvent($config, $tmpFiles);

        $this->assertInstanceOf(ConfigInterface::class, $unclosedResourcesEvent->getConfig());

        foreach ($unclosedResourcesEvent->getTmpFiles() as $tmpFile) {
            $this->assertInstanceOf(TmpFileInterface::class, $tmpFile);
        }
    }
}