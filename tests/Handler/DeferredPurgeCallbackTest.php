<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Handler\DeferredPurgeHandler\DeferredPurgeCallback;
use TmpFileManager\TmpFileManager;

class DeferredPurgeCallbackTest extends TestCase
{
    public function testDeferredPurgeCallback(): void
    {
        $tmpFileManager = new TmpFileManager();
        $deferredPurgeCallback = new DeferredPurgeCallback($tmpFileManager);
        $tmpFile = $tmpFileManager->create();
        $deferredPurgeCallback();

        $this->assertFileDoesNotExist($tmpFile->getFilename());
    }
}
