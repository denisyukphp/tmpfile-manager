<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use TmpFileManager\TmpFileManager;
use TmpFileManager\Config\Config;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use Symfony\Component\Filesystem\Filesystem as Fs;
use PHPUnit\Framework\TestCase;

class GarbageCollectionHandlerTest extends TestCase
{
    public function testGarbageCollectionHandler(): void
    {
        $tmpFileManager = new TmpFileManager();

        $fs = new Fs();

        $tmpFile = $tmpFileManager->create();

        $fs->touch($tmpFile->getFilename(), time() - 3600);

        $config = new Config(
            garbageCollectionProbability: 100,
            garbageCollectionLifetime: 0,
        );

        $garbageCollectionHandler = new GarbageCollectionHandler();

        $garbageCollectionHandler->handle($config);

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
