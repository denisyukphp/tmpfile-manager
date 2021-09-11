<?php

namespace TmpFileManager\Tests\TmpFileHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\TmpFileManager;

class FilesystemTest extends TestCase
{
    public function testGetTmpFileName(): void
    {
        $filesystem = Filesystem::create();

        $filename = $filesystem->getTmpFileName(sys_get_temp_dir(), 'php');

        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testExistsTmpFile(): void
    {
        $filesystem = Filesystem::create();

        $manager = new TmpFileManager();

        $tmpFile = $manager->createTmpFile();

        $this->assertTrue($filesystem->existsTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $filesystem = Filesystem::create();

        $manager = new TmpFileManager();

        $tmpFile = $manager->createTmpFile();

        $filesystem->removeTmpFile($tmpFile);

        $this->assertFileNotExists($tmpFile);
    }
}
