<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFileManager\Config\Config;
use TmpFileManager\Handler\GarbageCollectionHandler\GarbageCollectionHandler;
use TmpFileManager\TmpFileManager;

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
