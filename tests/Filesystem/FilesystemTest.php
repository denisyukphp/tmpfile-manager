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

    public function testCopySplFileInfo(): void
    {
        $filesystem = Filesystem::create();

        $manager = new TmpFileManager();

        $splFileInfo = new \SplFileInfo($manager->createTmpFile());

        file_put_contents($splFileInfo, 'Meow!');

        $tmpFile = $manager->createTmpFile();

        $filesystem->copySplFileInfo($splFileInfo, $tmpFile);

        $data = file_get_contents($tmpFile);

        $this->assertSame('Meow!', $data);
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
