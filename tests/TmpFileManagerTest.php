<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFileInterface;

use TmpFileManager\ConfigInterface;
use TmpFileManager\ContainerInterface;
use TmpFileManager\TmpFileHandlerInterface;
use TmpFileManager\TmpFileManager;
use TmpFileManager\TmpFileContextCallbackException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

class TmpFileManagerTest extends TestCase
{
    /**
     * @var TmpFileManager
     */
    private $tmpFileManager;

    public function setUp()
    {
        $this->tmpFileManager = new TmpFileManager();
    }

    public function testServices()
    {
        $this->assertInstanceOf(ConfigInterface::class, $this->tmpFileManager->getConfig());
        $this->assertInstanceOf(ContainerInterface::class, $this->tmpFileManager->getContainer());
        $this->assertInstanceOf(TmpFileHandlerInterface::class, $this->tmpFileManager->getTmpFileHandler());
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->tmpFileManager->getEventDispatcher());
    }

    public function testCreateTmpFile(): TmpFileInterface
    {
        $tmpFile = $this->tmpFileManager->createTmpFile();

        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    public function testCreateTmpFileContext()
    {
        $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            $this->assertFileExists($tmpFile);
        });
    }

    public function testCreateTmpFileContextException()
    {
        $this->expectException(TmpFileContextCallbackException::class);

        $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return $tmpFile;
        });
    }

    public function testCreateTmpFileContextNotExists()
    {
        $splFileInfo = $this->tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return new \SplFileInfo($tmpFile);
        });

        $this->assertFileNotExists($splFileInfo);
    }

    /**
     * @depends testCreateTmpFile
     *
     * @param TmpFileInterface $tmpFile
     */
    public function testRemoveTmpFile(TmpFileInterface $tmpFile)
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