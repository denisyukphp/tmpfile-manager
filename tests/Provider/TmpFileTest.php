<?php

namespace TmpFileManager\Tests\TmpFile;

use PHPUnit\Framework\TestCase;
use TmpFileManager\TmpFile\TmpFile;
use TmpFileManager\TmpFile\TmpFileInterface;
use TmpFile\TmpFileInterface as BaseTmpFileInterface;

class TmpFileTest extends TestCase
{
    public function testSuccess(): void
    {
        $this->assertTrue(is_a(TmpFile::class, TmpFileInterface::class, true));
        $this->assertTrue(is_a(TmpFile::class, BaseTmpFileInterface::class, true));
    }
}
