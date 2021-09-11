<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;

class TmpFileManagerTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        $this->assertFileExists($tmpFile);
    }

    public function testCreateTmpFileContext(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            $this->assertFileExists($tmpFile);
        });

        $tmpFilesCount = $tmpFileManager->getContainer()->getTmpFilesCount();

        $this->assertSame(0, $tmpFilesCount);
    }

    public function testRemoveTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        $tmpFileManager->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }

    public function testPurge(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        $tmpFileManager->purge();

        $this->assertFileNotExists($tmpFile);
    }
}
