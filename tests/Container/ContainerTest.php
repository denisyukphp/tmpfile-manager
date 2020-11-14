<?php

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Container\Container;

class ContainerTest extends TestCase
{
    public function testAddTmpFile()
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertCount(1, $tmpFiles);
    }

    public function testHasTmpFile()
    {
        $container = new Container();

        $tmpFile = new TmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertIsBool($container->hasTmpFile($tmpFile));
        $this->assertTrue($container->hasTmpFile($tmpFile));
    }

    public function testRemoveTmpFile()
    {
        $container = new Container();

        $tmpFile = new TmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertCount(1, $container->getTmpFiles());

        $container->removeTmpFile($tmpFile);

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testGetTmpFiles()
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertIsArray($tmpFiles);
        $this->assertNotEmpty($tmpFiles);
        $this->assertInstanceOf(TmpFileInterface::class, $tmpFiles[0]);
    }

    public function testGetTmpFilesCount()
    {
        $container = new Container();

        $container->addTmpFile(new TmpFile());

        $this->assertIsInt($container->getTmpFilesCount());
        $this->assertSame(1, $container->getTmpFilesCount());
    }
}
