<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Filesystem;

use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\TmpFileManager;
use PHPUnit\Framework\TestCase;

class FilesystemTest extends TestCase
{
    public function testGetTmpFileName(): void
    {
        $filesystem = new Filesystem();

        $filename = $filesystem->getTmpFileName(sys_get_temp_dir(), 'php');

        $this->assertFileExists($filename);

        unlink($filename);
    }

    public function testExistsTmpFile(): void
    {
        $filesystem = new Filesystem();

        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->create();

        $this->assertTrue($filesystem->existsTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $filesystem = new Filesystem();

        $tmpFileManager = new TmpFileManager();

        $tmpFile = $tmpFileManager->create();

        $filesystem->removeTmpFile($tmpFile);

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
