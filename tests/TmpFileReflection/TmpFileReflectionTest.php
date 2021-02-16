<?php

namespace TmpFileManager\Tests\TmpFileReflection;

use TmpFile\TmpFile;
use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFileReflection\TmpFileReflection;

class TmpFileReflectionTest extends TestCase
{
    public function testChangeFilename(): void
    {
        $tmpFileReflection = new TmpFileReflection(TmpFile::class);

        $tmpFile = $tmpFileReflection->changeFilename('Meow!');

        $this->assertInstanceOf(TmpFile::class, $tmpFile);
        $this->assertSame('Meow!', (string) $tmpFile);
    }
}
