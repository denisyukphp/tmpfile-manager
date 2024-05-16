<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler\GarbageCollectionHandler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\Handler\GarbageCollectionHandler\Processor\SyncProcessor;

final class GarbageCollectionHandlerTest extends TestCase
{
    public function testGarbageCollectionHandler(): void
    {
        $fs = new Fs();
        $tmpFile = $fs->tempnam(sys_get_temp_dir(), 'php');
        $fs->touch($tmpFile, time() - 7_200);

        $handler = new GarbageCollectionHandler(
            probability: 100,
            divisor: 1,
            lifetime: 3_600,
            processor: new SyncProcessor(),
        );
        $handler->handle(sys_get_temp_dir(), 'php');

        $this->assertFileDoesNotExist($tmpFile);
    }
}
