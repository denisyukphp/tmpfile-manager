<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;
use TmpFile\TmpFileInterface;
use TmpFileManager\Container;
use TmpFileManager\ContainerInterface;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        $this->container = new Container();
    }

    public function testAdd(): TmpFileInterface
    {
        $tmpFile = new TmpFile();

        $this->container->addTmpFile($tmpFile);

        $this->assertTrue($this->container->hasTmpFile($tmpFile));

        return $tmpFile;
    }

    /**
     * @depends testAdd
     *
     * @param TmpFileInterface $tmpFile
     */
    public function testRemove(TmpFileInterface $tmpFile)
    {
        $this->container->removeTmpFile($tmpFile);

        $this->assertFalse($this->container->hasTmpFile($tmpFile));
    }

    public function testContains()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->container->addTmpFile(new TmpFile());
        }

        $this->assertNotEmpty($this->container->getTmpFiles());
        $this->assertEquals(10, $this->container->getTmpFilesCount());
    }
}