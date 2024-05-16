<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Event\TmpFileManagerPostLoad;
use TmpFileManager\Event\TmpFilePostRemove;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Tests\Event\TmpFileEventSpy;
use TmpFileManager\Tests\Event\TmpFileManagerEventSpy;
use TmpFileManager\TmpFile;
use TmpFileManager\TmpFileManagerBuilder;

final class TmpFileMangerTest extends TestCase
{
    public function testCreateTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();

        $tmpFile = $tmpFileManager->create();

        $this->assertFileExists($tmpFile->getFilename());
    }

    public function testLoadTmpFile(): void
    {
        $fs = new Filesystem();
        $tmpFile = $fs->createTmpFile(sys_get_temp_dir(), 'php');
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPostLoad::class, $spy)
            ->build()
        ;

        try {
            $tmpFileManager->load($tmpFile);
        } catch (\Throwable) {
            if ($fs->existsTmpFile($tmpFile)) {
                $fs->removeTmpFile($tmpFile);
            }
        }

        $this->assertEquals(1, $spy->getTmpFilesCount());
    }

    public function testLoadTheSameTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Temp file ".+" has been already added.$/');

        $tmpFileManager->load($tmpFileManager->create());
    }

    public function testLoadDoesNotExistTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Temp file ".+" doesn\'t exist.$/');

        $tmpFileManager->load(new TmpFile('meow.txt'));
    }

    public function testIsolateTmpFile(): void
    {
        $spy = new TmpFileEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFilePostRemove::class, $spy)
            ->build()
        ;

        $tmpFileManager->isolate(function (TmpFileInterface $tmpFile): void {
            $this->assertFileExists($tmpFile->getFilename());
        });

        $this->assertEquals(1, $spy->getEventsCount());

        foreach ($spy->getTmpFiles() as $tmpFile) {
            $this->assertFileDoesNotExist($tmpFile->getFilename());
        }
    }

    public function testRemoveTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();
        $tmpFile = $tmpFileManager->create();

        $tmpFileManager->remove($tmpFile);

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }

    public function testRemoveDoesNotExistTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Temp file ".+" has been already removed.$/');

        $tmpFileManager->remove(new TmpFile('meow.txt'));
    }

    public function testRemoveDoesNotAddTmpFile(): void
    {
        $fs = new Filesystem();
        $tmpFile = $fs->createTmpFile(sys_get_temp_dir(), 'php');
        $tmpFileManager = (new TmpFileManagerBuilder())->build();

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/^Temp file ".+" wasn\'t create through temp file manager.$/');

        try {
            $tmpFileManager->remove($tmpFile);
        } finally {
            if ($fs->existsTmpFile($tmpFile)) {
                $fs->removeTmpFile($tmpFile);
            }
        }
    }

    public function testPurgeTmpFile(): void
    {
        $tmpFileManager = (new TmpFileManagerBuilder())->build();
        $tmpFile = $tmpFileManager->create();

        $tmpFileManager->purge();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }

    public function testPurgeTmpFileAfterLoad(): void
    {
        $fs = new Filesystem();
        $tmpFile = $fs->createTmpFile(sys_get_temp_dir(), 'php');
        $tmpFileManager = (new TmpFileManagerBuilder())->build();
        $tmpFileManager->load($tmpFile);

        $tmpFileManager->purge();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
