<?php

namespace TmpFileManager\Tests\TmpFileHandler;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileHandler\TmpFileHandler;
use TmpFileManager\Tests\SplFileInfoBuilder;

class TmpFileHandlerTest extends TestCase
{
    public function testGetTmpFileName(): void
    {
        $tmpFileHandler = TmpFileHandler::create();

        $filename = $tmpFileHandler->getTmpFileName(sys_get_temp_dir(), 'php');

        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testExistsTmpFile(): void
    {
        $tmpFileHandler = TmpFileHandler::create();

        $tmpFile = new TmpFile();

        $this->assertTrue($tmpFileHandler->existsTmpFile($tmpFile));
    }

    public function testCopySplFileInfo(): void
    {
        $tmpFileHandler = TmpFileHandler::create();

        $splFileInfo = SplFileInfoBuilder::create()->addData('Meow!')->build();

        $tmpFile = new TmpFile();

        $tmpFileHandler->copySplFileInfo($splFileInfo, $tmpFile);

        $data = file_get_contents($tmpFile);

        $this->assertSame('Meow!', $data);
    }

    public function testRemoveTmpFile(): void
    {
        $tmpFileHandler = TmpFileHandler::create();

        $tmpFile = new TmpFile();

        $tmpFileHandler->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}
