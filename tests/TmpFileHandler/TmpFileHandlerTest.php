<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFileManager\TmpFileHandler\TmpFileHandler;
use Symfony\Component\Filesystem\Filesystem;

class TmpFileHandlerTest extends TestCase
{
    public function testGetTmpFileName()
    {
        $tmpFileHandler = new TmpFileHandler(
            new Filesystem()
        );

        $filename = $tmpFileHandler->getTmpFileName(sys_get_temp_dir(), 'php');

        $this->assertIsString($filename);
        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testExistsTmpFile()
    {
        $tmpFileHandler = new TmpFileHandler(
            new Filesystem()
        );

        $tmpFile = new TmpFile();

        $this->assertIsBool($tmpFileHandler->existsTmpFile($tmpFile));
        $this->assertTrue($tmpFileHandler->existsTmpFile($tmpFile));
    }

    public function testRemoveTmpFile()
    {
        $tmpFileHandler = new TmpFileHandler(
            new Filesystem()
        );

        $tmpFile = new TmpFile();

        $tmpFileHandler->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}
