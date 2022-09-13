<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Container\Container;
use TmpFileManager\TmpFileManager;

class TmpFileManagerTest extends TestCase
{
    public function testCreate(): void
    {
        $tmpFileManager = new TmpFileManager();
        $tmpFile = $tmpFileManager->create();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testIsolate(): void
    {
        $container = new Container();
        $tmpFileManager = new TmpFileManager(container: $container);

        $tmpFileManager->isolate(function (TmpFileInterface $tmpFile) {
            $this->assertFileExists($tmpFile->getFilename());
        });

        $this->assertEquals(0, $container->getTmpFilesCount());
    }

    public function testRemoveTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();
        $tmpFile = $tmpFileManager->create();
        $tmpFileManager->remove($tmpFile);

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }

    public function testPurge(): void
    {
        $tmpFileManager = new TmpFileManager();
        $tmpFile = $tmpFileManager->create();
        $tmpFileManager->purge();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
