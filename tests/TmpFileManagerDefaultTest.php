<?php

use TmpFile\TmpFile;
use TmpFileManager\TmpFileManager;
use TmpFileManager\ConfigBuilder;
use PHPUnit\Framework\TestCase;

class TmpFileManagerDefaultTest extends TestCase
{
    /** @var TmpFileManager */
    protected $tmpFileManager;

    public function setUp()
    {
        $config = (new ConfigBuilder())
            ->setTmpFilePrefix('test')
            ->build()
        ;

        $this->tmpFileManager = new TmpFileManager($config);
    }

    public function testCreateTmpFile(): TmpFile
    {
        $tmpFile = $this->tmpFileManager->createTmpFile();

        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    public function testCreateTmpFileContext(): void
    {
        $this->tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
            $this->assertFileExists($tmpFile);
        });
    }

    /**
     * @expectedException \TmpFileManager\TmpFileContextCallbackException
     */
    public function testCreateTmpFileContextException(): void
    {
        $this->tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
            return $tmpFile;
        });
    }

    public function testCreateTmpFileContextNotExists(): void
    {
        $splFileInfo = $this->tmpFileManager->createTmpFileContext(function (TmpFile $tmpFile) {
            return new \SplFileInfo($tmpFile);
        });

        $this->assertFileNotExists($splFileInfo);
    }

    /**
     * @param TmpFile $tmpFile
     *
     * @depends testCreateTmpFile
     */
    public function testRemoveTmpFile(TmpFile $tmpFile): void
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