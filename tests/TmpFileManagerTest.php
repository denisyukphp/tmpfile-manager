<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Exception\FileNotUploadedException;

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

    public function testCreateTmpFileFromSplFileInfo(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        $splFileInfo = new \SplFileInfo($tmpFile);

        file_put_contents($splFileInfo, 'Meow!');

        $createdTmpFile = $tmpFileManager->createTmpFileFromSplFileInfo($splFileInfo);

        $data = file_get_contents($createdTmpFile);

        $this->assertSame('Meow!', $data);
    }

    public function testCreateTmpFileFromUploadedFileFailure(): void
    {
        $tmpFileManager = new TmpFileManager();

        $this->expectException(FileNotUploadedException::class);

        $tmpFileManager->createTmpFileFromUploadedFile('Meow!');
    }

    public function testCopyTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        file_put_contents($tmpFile, 'Meow!');

        $copiedTmpFile = $tmpFileManager->copyTmpFile($tmpFile);

        $data = file_get_contents($copiedTmpFile);

        $this->assertSame('Meow!', $data);
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
