<?php

namespace TmpFileManager\Tests\Container;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Container\Container;

class ContainerTest extends TestCase
{
    public function testAddTmpFile(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $container->addTmpFile($manager->createTmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertCount(1, $tmpFiles);
    }

    public function testHasTmpFile(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $tmpFile = $manager->createTmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertTrue($container->hasTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $tmpFile = $manager->createTmpFile();

        $container->addTmpFile($tmpFile);

        $this->assertCount(1, $container->getTmpFiles());

        $container->removeTmpFile($tmpFile);

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testGetTmpFiles(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $container->addTmpFile($manager->createTmpFile());

        $tmpFiles = $container->getTmpFiles();

        $this->assertNotEmpty($tmpFiles);

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFiles[0]);
    }

    public function testGetTmpFilesCount(): void
    {
        $manager = new TmpFileManager();

        $container = new Container();

        $container->addTmpFile($manager->createTmpFile());

        $this->assertSame(1, $container->getTmpFilesCount());
    }
}
