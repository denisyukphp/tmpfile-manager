<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TmpFileManagerTest extends TestCase
{
    public function testServices()
    {
        $tmpFileManager = new TmpFileManager();

        $this->assertInstanceOf(ConfigInterface::class, $tmpFileManager->getConfig());
        $this->assertInstanceOf(ContainerInterface::class, $tmpFileManager->getContainer());
        $this->assertInstanceOf(TmpFileHandlerInterface::class, $tmpFileManager->getTmpFileHandler());
        $this->assertInstanceOf(EventDispatcherInterface::class, $tmpFileManager->getEventDispatcher());
    }

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

        $tmpFile = new TmpFile();

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
