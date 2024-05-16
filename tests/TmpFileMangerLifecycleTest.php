<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Event\TmpFileManagerOnFinish;
use TmpFileManager\Event\TmpFileManagerOnStart;
use TmpFileManager\Event\TmpFileManagerPostCreate;
use TmpFileManager\Event\TmpFileManagerPostLoad;
use TmpFileManager\Event\TmpFileManagerPostPurge;
use TmpFileManager\Event\TmpFileManagerPreCreate;
use TmpFileManager\Event\TmpFileManagerPreLoad;
use TmpFileManager\Event\TmpFileManagerPrePurge;
use TmpFileManager\Event\TmpFileOnCreate;
use TmpFileManager\Event\TmpFileOnLoad;
use TmpFileManager\Event\TmpFilePostRemove;
use TmpFileManager\Event\TmpFilePreRemove;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Tests\Event\TmpFileEventSpy;
use TmpFileManager\Tests\Event\TmpFileManagerEventSpy;
use TmpFileManager\TmpFileManagerBuilder;

final class TmpFileMangerLifecycleTest extends TestCase
{
    public function testTmpFileManagerOnStart(): void
    {
        $spy = new TmpFileManagerEventSpy();
        (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerOnStart::class, $spy)
            ->build()
        ;

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerPreCreate(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPreCreate::class, $spy)
            ->build()
        ;

        $tmpFileManager->create();

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileOnCreate(): void
    {
        $spy = new TmpFileEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileOnCreate::class, $spy)
            ->build()
        ;

        $tmpFileManager->create();

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerPostCreate(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPostCreate::class, $spy)
            ->build()
        ;

        $tmpFileManager->create();

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerPreLoad(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $filesystem = new Filesystem();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPreLoad::class, $spy)
            ->build()
        ;

        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');
        $tmpFileManager->load($tmpFile);

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileOnLoad(): void
    {
        $spy = new TmpFileEventSpy();
        $filesystem = new Filesystem();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileOnLoad::class, $spy)
            ->build()
        ;

        $tmpFileManager->load(...[
            $filesystem->createTmpFile(sys_get_temp_dir(), 'php'),
            $filesystem->createTmpFile(sys_get_temp_dir(), 'php'),
            $filesystem->createTmpFile(sys_get_temp_dir(), 'php'),
        ]);

        $this->assertEquals(3, $spy->getEventsCount());
    }

    public function testTmpFileManagerPostLoad(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $filesystem = new Filesystem();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPostLoad::class, $spy)
            ->build()
        ;

        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');
        $tmpFileManager->load($tmpFile);

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFilePreRemove(): void
    {
        $spy = new TmpFileEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFilePreRemove::class, $spy)
            ->build()
        ;

        $tmpFile = $tmpFileManager->create();
        $tmpFileManager->remove($tmpFile);

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFilePostRemove(): void
    {
        $spy = new TmpFileEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFilePostRemove::class, $spy)
            ->build()
        ;

        $tmpFile = $tmpFileManager->create();
        $tmpFileManager->remove($tmpFile);

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerPrePurge(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPrePurge::class, $spy)
            ->build()
        ;

        $tmpFileManager->purge();

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerPostPurge(): void
    {
        $spy = new TmpFileManagerEventSpy();
        $tmpFileManager = (new TmpFileManagerBuilder())
            ->withEventListener(TmpFileManagerPostPurge::class, $spy)
            ->build()
        ;

        $tmpFileManager->purge();

        $this->assertEquals(1, $spy->getEventsCount());
    }

    public function testTmpFileManagerOnFinish(): void
    {
        $spy = new TmpFileManagerEventSpy();
        (new TmpFileManagerBuilder())
            ->withoutAutoPurge()
            ->withEventListener(TmpFileManagerOnFinish::class, $spy)
            ->build()
        ;

        $this->assertEquals(1, $spy->getEventsCount());
    }
}
