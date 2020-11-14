<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;
use TmpFileManager\TmpFileManagerInterface;
use TmpFileManager\TmpFileManagerServicesInterface;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\TmpFileHandler\TmpFileHandlerInterface;
use TmpFileManager\Exception\TmpFileContextCallbackException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TmpFileManagerTest extends TestCase
{
    public function testInstances()
    {
        $tmpFileManager = new TmpFileManager();

        $this->assertInstanceOf(TmpFileManagerInterface::class, $tmpFileManager);
        $this->assertInstanceOf(TmpFileManagerServicesInterface::class, $tmpFileManager);
        $this->assertInstanceOf(ConfigInterface::class, $tmpFileManager->getConfig());
        $this->assertInstanceOf(ContainerInterface::class, $tmpFileManager->getContainer());
        $this->assertInstanceOf(TmpFileHandlerInterface::class, $tmpFileManager->getTmpFileHandler());
        $this->assertInstanceOf(EventDispatcherInterface::class, $tmpFileManager->getEventDispatcher());
    }

    public function testCreateTmpFile(): TmpFileInterface
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->createTmpFile();

        $this->assertFileExists($tmpFile);

        return $tmpFile;
    }

    public function testCreateTmpFileContext()
    {
        $tmpFileManager = new TmpFileManager();

        $file = $tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            $this->assertFileExists($tmpFile);

            return new \SplFileInfo($tmpFile);
        });

        $this->assertFileNotExists($file);
    }

    public function testCreateTmpFileContextException()
    {
        $tmpFileManager = new TmpFileManager();

        $this->expectException(TmpFileContextCallbackException::class);

        $tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return $tmpFile;
        });
    }

    public function testCreateTmpFileContextNotExists()
    {
        $tmpFileManager = new TmpFileManager();

        $splFileInfo = $tmpFileManager->createTmpFileContext(function (TmpFileInterface $tmpFile) {
            return new \SplFileInfo($tmpFile);
        });

        $this->assertFileNotExists($splFileInfo);
    }

    public function testRemoveTmpFile()
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFile = new TmpFile();

        $tmpFileManager->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }

    public function testPurge(): void
    {
        $tmpFileManager = new TmpFileManager();

        $tmpFiles = [];

        for ($i = 0; $i < 5; $i++) {
            $tmpFiles[] = $tmpFileManager->createTmpFile();
        }

        $tmpFileManager->purge();

        foreach ($tmpFiles as $tmpFile) {
            $this->assertFileNotExists($tmpFile);
        }
    }
}
