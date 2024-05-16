<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler\GarbageCollectionHandler\Processor;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFileManager\Handler\GarbageCollectionHandler\Processor\AsyncProcessor;

/**
 * @requires OS Linux
 */
final class AsyncProcessorTest extends TestCase
{
    public function testSyncProcessor(): void
    {
        $fs = new Fs();
        $tmpFile = $fs->tempnam(sys_get_temp_dir(), 'php');
        $fs->touch($tmpFile, time() - 7_200);

        $processor = new AsyncProcessor(
            isParallel: false,
        );
        $processor->process(sys_get_temp_dir(), 'php', 3_600);

        $this->assertFileDoesNotExist($tmpFile);
    }
}
