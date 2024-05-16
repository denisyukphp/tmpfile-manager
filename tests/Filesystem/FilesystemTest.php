<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Filesystem\Filesystem;

final class FilesystemTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $filesystem = new Filesystem();

        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');

        $this->assertFileExists($tmpFile->getFilename());

        $filesystem->removeTmpFile($tmpFile);
    }

    public function testExistTmpFile(): void
    {
        $filesystem = new Filesystem();

        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');

        $this->assertTrue($filesystem->existsTmpFile($tmpFile));

        $filesystem->removeTmpFile($tmpFile);
    }

    public function testRemoveTmpFile(): void
    {
        $filesystem = new Filesystem();

        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');

        $filesystem->removeTmpFile($tmpFile);

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
