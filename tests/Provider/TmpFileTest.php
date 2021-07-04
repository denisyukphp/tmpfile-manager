<?php

namespace TmpFileManager\Tests\Provider;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Provider\TmpFile;

class TmpFileTest extends TestCase
{
    public function testSuccess(): void
    {
        $this->assertTrue(is_a(TmpFile::class, TmpFileInterface::class, true));
    }
}
