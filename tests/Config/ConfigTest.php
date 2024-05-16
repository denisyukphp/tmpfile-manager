<?php

declare(strict_types=1);

namespace TmpFileManager\Tests\Config;

use PHPUnit\Framework\TestCase;
use TmpFileManager\Config\Config;

final class ConfigTest extends TestCase
{
    public function testArgs(): void
    {
        $config = new Config(
            tmpFileDir: sys_get_temp_dir(),
            tmpFilePrefix: 'php',
        );

        $this->assertSame(sys_get_temp_dir(), $config->getTmpFileDir());
        $this->assertSame('php', $config->getTmpFilePrefix());
    }
}
