<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

final class Config implements ConfigInterface
{
    private string $tmpFileDirectory;

    public function __construct(
        ?string $tmpFileDirectory = null,
        private string $tmpFilePrefix = 'php',
    ) {
        $this->tmpFileDirectory = $tmpFileDirectory ?? sys_get_temp_dir();
    }

    public function getTmpFileDirectory(): string
    {
        return $this->tmpFileDirectory;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }
}
