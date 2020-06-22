<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;
use TmpFileManager\DeferredPurgeHandler\DeferredPurgeCallback;
use PHPUnit\Framework\TestCase;

class DeferredPurgeCallbackTest extends TestCase
{
    public function testPurge()
    {
        $tmpFileManager = new TmpFileManager();
        $deferredPurgeCallback = new DeferredPurgeCallback($tmpFileManager);

        $tmpFile = $tmpFileManager->createTmpFile();

        $this->assertIsCallable($deferredPurgeCallback);

        $deferredPurgeCallback();

        $this->assertFileNotExists($tmpFile);
    }
}