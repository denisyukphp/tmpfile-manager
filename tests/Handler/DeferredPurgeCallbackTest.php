<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use TmpFileManager\TmpFileManager;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeCallback;
use PHPUnit\Framework\TestCase;

class DeferredPurgeCallbackTest extends TestCase
{
    public function testDeferredPurgeCallback(): void
    {
        $tmpFileManager = new TmpFileManager();

        $deferredPurgeCallback = new DeferredPurgeCallback($tmpFileManager);

        $tmpFile = $tmpFileManager->create();

        $this->assertIsCallable($deferredPurgeCallback);

        $deferredPurgeCallback();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
