<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Container;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Container\Container;
use TmpFileManager\TmpFile;

final class ContainerTest extends TestCase
{
    public function testAddTmpFile(): void
    {
        $container = new Container();
        $tmpFile = new TmpFile('meow.txt');

        $container->addTmpFile($tmpFile);

        $this->assertCount(1, $container->getTmpFiles());
    }

    public function testAddTheSameTmpFile(): void
    {
        $container = new Container();
        $tmpFile = new TmpFile('meow.txt');
        $container->addTmpFile($tmpFile);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Temp file "meow.txt" has been already added.');

        $container->addTmpFile($tmpFile);
    }

    public function testHasTmpFile(): void
    {
        $container = new Container();
        $tmpFile = new TmpFile('meow.txt');

        $container->addTmpFile($tmpFile);

        $this->assertTrue($container->hasTmpFile($tmpFile));
    }

    public function testRemoveTmpFile(): void
    {
        $container = new Container();
        $tmpFile = new TmpFile('meow.txt');
        $container->addTmpFile($tmpFile);

        $container->removeTmpFile($tmpFile);

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testRemoveNotAddedTmpFile(): void
    {
        $container = new Container();
        $tmpFile = new TmpFile('meow.txt');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Temp file "meow.txt" hasn\'t been added yet.');

        $container->removeTmpFile($tmpFile);
    }

    public function testCleatTmpFiles(): void
    {
        $container = new Container();
        $container->addTmpFile(new TmpFile('cat.jpg'));
        $container->addTmpFile(new TmpFile('dog.jpg'));
        $container->addTmpFile(new TmpFile('fish.jpg'));

        $container->clearTmpFiles();

        $this->assertCount(0, $container->getTmpFiles());
    }

    public function testGetTmpFiles(): void
    {
        $container = new Container();
        $container->addTmpFile(new TmpFile('cat.jpg'));
        $container->addTmpFile(new TmpFile('dog.jpg'));
        $container->addTmpFile(new TmpFile('fish.jpg'));

        $tmpFiles = $container->getTmpFiles();

        $this->assertNotEmpty($tmpFiles);
    }
}
