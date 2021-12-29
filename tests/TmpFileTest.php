<?php

declare(strict_types=1);

namespace TmpFileManager\Tests;

use TmpFileManager\TmpFile;
use PHPUnit\Framework\TestCase;

class TmpFileTest extends TestCase
{
    public function testTmpFile(): void
    {
        $tmpFile = new TmpFile('filename');

        $this->assertSame('filename', $tmpFile->getFilename());
        $this->assertSame('filename', (string) $tmpFile);
    }
}
