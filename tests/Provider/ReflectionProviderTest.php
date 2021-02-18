<?php

namespace TmpFileManager\Tests\Provider;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Provider\ReflectionProvider;
use TmpFileManager\TmpFile\TmpFileInterface;

class ReflectionProviderTest extends TestCase
{
    public function testGetTmpFile(): void
    {
        $provider = new ReflectionProvider();

        $tmpFile = $provider->getTmpFile('Meow!');

        $this->assertSame('Meow!', (string) $tmpFile);
        $this->assertInstanceOf(TmpFileInterface::class, $tmpFile);
    }
}
