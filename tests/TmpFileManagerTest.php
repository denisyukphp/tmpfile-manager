<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\Provider\ProviderInterface;
use TmpFileManager\TmpFile\TmpFileInterface;
use TmpFileManager\Exception\FileNotUploadedException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TmpFileManagerTest extends TestCase
{
    public function testServices()
    {
        $tmpFileManager = new TmpFileManager();

        $this->assertInstanceOf(ConfigInterface::class, $tmpFileManager->getConfig());
        $this->assertInstanceOf(ContainerInterface::class, $tmpFileManager->getContainer());
        $this->assertInstanceOf(FilesystemInterface::class, $tmpFileManager->getFilesystem());
        $this->assertInstanceOf(EventDispatcherInterface::class, $tmpFileManager->getEventDispatcher());
        $this->assertInstanceOf(ProviderInterface::class, $tmpFileManager->getProvider());
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
