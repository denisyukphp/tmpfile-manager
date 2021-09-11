<?php

namespace TmpFileManager\Tests\Provider;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Provider\ReflectionProvider;

class ReflectionProviderTest extends TestCase
{
    public function testGetTmpFile(): void
    {
        $filesystem = Filesystem::create();

        $provider = new ReflectionProvider();

        $filename = $filesystem->getTmpFileName(sys_get_temp_dir(), 'php');

        $tmpFile = $provider->getTmpFile($filename);

        $this->assertSame($filename, $tmpFile->getFilename());

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFile);

        unlink($tmpFile->getFilename());
    }

    public function testGetNotExistsTmpFile(): void
    {
        $provider = new ReflectionProvider();

        $this->expectException(\LogicException::class);

        $provider->getTmpFile('Meow!');
    }
}
