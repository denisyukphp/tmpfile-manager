<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileHandler;
use TmpFileManager\TmpFileHandlerInterface;
use Symfony\Component\Filesystem\Filesystem;
use PHPUnit\Framework\TestCase;

class TmpFileHandlerTest extends TestCase
{
    /**
     * @var TmpFileHandlerInterface
     */
    private $tmpFileHandler;

    public function setUp()
    {
        $this->tmpFileHandler = new TmpFileHandler(new Filesystem());
    }

    public function testCreate()
    {
        $filename = $this->tmpFileHandler->getTmpFileName(sys_get_temp_dir(), 'php');

        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testExists(): TmpFileInterface
    {
        $tmpFile = new TmpFile();

        $this->assertTrue($this->tmpFileHandler->existsTmpFile($tmpFile));

        return $tmpFile;
    }

    /**
     * @depends testExists
     *
     * @param TmpFileInterface $tmpFile
     */
    public function testRemove(TmpFileInterface $tmpFile)
    {
        $this->tmpFileHandler->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}