<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFileManager\Event\TmpFileManagerPostPurge;
use TmpFileManager\Event\TmpFileManagerPrePurge;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\Processor\SyncProcessor;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;
use TmpFileManager\Tests\Event\TmpFileManagerEventSpy;
use TmpFileManager\TmpFile;
use TmpFileManager\TmpFileManagerBuilder;

final class TmpFileMangerBuilderTest extends TestCase
{
    public function testBuildWithTmpFileDir(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withTmpFileDir(sys_get_temp_dir())
            ->build()
        ;

        $tmpFile = $tmpFileManager->create();

        $this->assertStringStartsWith(sys_get_temp_dir(), $tmpFile->getFilename());
    }

    public function testBuildWithTmpFilePrefix(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withTmpFileDir(sys_get_temp_dir())
            ->withTmpFilePrefix('php')
            ->build()
        ;

        $tmpFile = $tmpFileManager->create();

        $this->assertStringStartsWith(sys_get_temp_dir().'/php', $tmpFile->getFilename());
    }

    public function testBuildTmpFileManagerWithoutAutoPurge(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withoutAutoPurge()
            ->withEventListener(TmpFileManagerPostPurge::class, $spy)
            ->build()
        ;

        $tmpFileManager->create();
        $tmpFileManager->purge();

        $this->assertEquals(0, $spy->getTmpFilesCount());
    }

    public function testBuildWithUnclosedResourcesHandler(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withUnclosedResourcesHandler(new UnclosedResourcesHandler())
            ->withEventListener(TmpFileManagerPrePurge::class, $spy)
            ->build()
        ;

        $tmpFile = $tmpFileManager->create();
        $fh = fopen($tmpFile->getFilename(), 'r');
        $tmpFileManager->purge();

        $this->assertFalse(\is_resource($fh));
        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testBuildWithGarbageCollectionHandler(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withoutAutoPurge()
            ->withGarbageCollectionHandler(new GarbageCollectionHandler(
                probability: 100,
                divisor: 1,
                lifetime: 3_600,
                processor: new SyncProcessor(),
            ))
            ->withEventListener(TmpFileManagerPostPurge::class, $spy)
            ->build()
        ;

        $fs = new Fs();
        $tmpFile = new TmpFile($fs->tempnam(sys_get_temp_dir(), 'php'));
        $fs->touch($tmpFile->getFilename(), time() - 7_200);
        $tmpFileManager->purge();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
        $this->assertEquals(1, $spy->getEventsCount());
    }
}
