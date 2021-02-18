<?php

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeCallback;

class DeferredPurgeCallbackTest extends TestCase
{
    public function testCallback(): void
    {
        $manager = new TmpFileManager();

        $callback = new DeferredPurgeCallback($manager);

        $tmpFile = $manager->createTmpFile();

        $this->assertIsCallable($callback);

        $callback();

        $this->assertFileNotExists($tmpFile);
    }
}
