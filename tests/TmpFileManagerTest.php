<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\TmpFileManager;
use Bulletproof\TmpFileManager\TmpFileManagerInterface;
use Bulletproof\TmpFileManager\TmpFileContextCallbackException;
use PHPUnit\Framework\TestCase;

class TmpFileManagerTest extends TestCase
{
    /**
     * @var TmpFileManagerInterface
     */
    protected $tmpFileManager;

    public function setUp()
    {
        $this->tmpFileManager = new TmpFileManager();
    }

    public function testCreateTmpFile(): TmpFileInterface
    {
        $tmpFile = $this->tmpFileManager->createTmpFile();

        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    public function testCreateTmpFileContext(): void
    {
        $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            $this->assertFileExists($tmpFile);
        });
    }

    public function testCreateTmpFileContextException(): void
    {
        $this->expectException(TmpFileContextCallbackException::class);

        $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return $tmpFile;
        });
    }

    public function testCreateTmpFileContextNotExists(): void
    {
        $splFileInfo = $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return new \SplFileInfo($tmpFile);
        });

        $this->assertFileNotExists($splFileInfo);
    }

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @depends testCreateTmpFile
     */
    public function testRemoveTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->tmpFileManager->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }

    public function testPurge(): void
    {
        $tmpFile = $this->tmpFileManager->createTmpFile();

        $this->tmpFileManager->purge();

        $this->assertFileNotExists($tmpFile);
    }
}