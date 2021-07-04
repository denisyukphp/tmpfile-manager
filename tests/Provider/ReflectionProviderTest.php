<?php

namespace TmpFileManager\Tests\Provider;

use PHPUnit\Framework\TestCase;
use TmpFile\TmpFileInterface;
use TmpFileManager\Provider\ReflectionProvider;

class ReflectionProviderTest extends TestCase
{
    public function testGetTmpFile(): void
    {
        $provider = new ReflectionProvider();

        $tmpFile = $provider->getTmpFile('Meow!');

        $this->assertSame('Meow!', $tmpFile->getFilename());

        $this->assertInstanceOf(TmpFileInterface::class, $tmpFile);
    }
}
