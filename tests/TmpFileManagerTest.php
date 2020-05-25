<?php

use TmpFile\TmpFile;
use TmpFileManager\TmpFileManager;
use TmpFileManager\ConfigBuilder;
use TmpFileManager\ConfigInterface;
use TmpFileManager\ContainerInterface;
use TmpFileManager\TmpFileHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use PHPUnit\Framework\TestCase;

class TmpFileManagerTest extends TestCase
{
    /** @var TmpFileManager */
    protected $tmpFileManager;

    public function setUp()
    {
        $config = (new ConfigBuilder())
            ->setTmpFilePrefix('test')
            ->setDeferredAutoPurge(false)
            ->build()
        ;

        $this->tmpFileManager = new TmpFileManager($config);
    }

    public function testGetters()
    {
        $this->assertInstanceOf(ConfigInterface::class, $this->tmpFileManager->getConfig());
        $this->assertInstanceOf(ContainerInterface::class, $this->tmpFileManager->getContainer());
        $this->assertInstanceOf(TmpFileHandlerInterface::class, $this->tmpFileManager->getTmpFileHandler());
        $this->assertInstanceOf(EventDispatcherInterface::class, $this->tmpFileManager->getEventDispatcher());
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