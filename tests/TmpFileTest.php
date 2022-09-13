<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFile;

class TmpFileTest extends TestCase
{
    public function testReturnFilename(): void
    {
        $tmpFile = new TmpFile('filename');

        $this->assertSame('filename', $tmpFile->getFilename());
    }

    public function testStringableBehavior(): void
    {
        $tmpFile = new TmpFile('filename');

        $this->assertSame('filename', (string) $tmpFile);
    }
}
