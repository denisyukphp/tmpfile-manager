<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Handler\UnclosedResourcesHandler;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Filesystem\Filesystem;
use TmpFileManager\Handler\UnclosedResourcesHandler\UnclosedResourcesHandler;

final class UnclosedResourcesHandlerTest extends TestCase
{
    public function testUnclosedResourcesHandler(): void
    {
        $filesystem = new Filesystem();
        $tmpFile = $filesystem->createTmpFile(sys_get_temp_dir(), 'php');
        $fh = fopen($tmpFile->getFilename(), 'r');

        try {
            $handler = new UnclosedResourcesHandler();
            $handler->handle([$tmpFile]);

            $this->assertFalse(\is_resource($fh));
        } finally {
            $filesystem->removeTmpFile($tmpFile);
        }
    }
}
