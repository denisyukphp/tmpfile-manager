<?php

namespace Bulletproof\TmpFileManager\Tests;

use Bulletproof\TmpFile\TmpFile;
use Bulletproof\TmpFile\TmpFileInterface;
use Bulletproof\TmpFileManager\TmpFileHandler;
use Bulletproof\TmpFileManager\TmpFileHandlerInterface;
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