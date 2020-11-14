<?php

namespace TmpFileManager\Tests\DeferredPurgeHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileManager;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeCallback;

class DeferredPurgeCallbackTest extends TestCase
{
    public function testCallback()
    {
        $tmpFileManager = new TmpFileManager();
        $deferredPurgeCallback = new DeferredPurgeCallback($tmpFileManager);

        $tmpFile = $tmpFileManager->createTmpFile();

        $this->assertIsCallable($deferredPurgeCallback);

        $deferredPurgeCallback();

        $this->assertFileNotExists($tmpFile);
    }
}
