<?php

namespace TmpFileManager\Tests\Container;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use PHPUnit\Framework\TestCase;
use TmpFileManager\Container\Container;

class ContainerTest extends TestCase
{
    public function testAddTmpFile(): void
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertCount(1, $tmpFiles);
    }

    public function testHasTmpFile(): void
    {
        $container = new Container();

        $tmpFile = new TmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertTrue($container->hasTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $container = new Container();

        $tmpFile = new TmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertCount(1, $container->getTmpFiles());

        $container->removeTmpFile($tmpFile);

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testGetTmpFiles(): void
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertNotEmpty($tmpFiles);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFiles[0]);
    }

    public function testGetTmpFilesCount(): void
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $this->assertSame(1, $container->getTmpFilesCount());
    }
}
