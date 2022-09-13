<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Container;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Container\Container;
use TmpFileManager\TmpFileManager;

class ContainerTest extends TestCase
{
    public function testAddTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();
        $container = new Container();
        $container->addTmpFile($tmpFileManager->create());

        $this->assertCount(1, $container->getTmpFiles());
    }

    public function testHasTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();
        $container = new Container();
        $tmpFile = $tmpFileManager->create();
        $container->addTmpFile($tmpFile);

        $this->assertTrue($container->hasTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $tmpFileManager = new TmpFileManager();
        $container = new Container();
        $tmpFile = $tmpFileManager->create();
        $container->addTmpFile($tmpFile);
        $container->removeTmpFile($tmpFile);

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testGetTmpFiles(): void
    {
        $tmpFileManager = new TmpFileManager();
        $container = new Container();
        $container->addTmpFile($tmpFileManager->create());
        $tmpFiles = $container->getTmpFiles();

        $this->assertNotEmpty($tmpFiles);
    }

    public function testGetTmpFilesCount(): void
    {
        $manager = new TmpFileManager();
        $container = new Container();
        $container->addTmpFile($manager->create());

        $this->assertEquals(1, $container->getTmpFilesCount());
    }
}
