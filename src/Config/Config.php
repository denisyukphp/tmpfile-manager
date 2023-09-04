<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

final class Config implements ConfigInterface
{
    public function __construct(
        private string $tmpFileDir,
        private string $tmpFilePrefix,
    ) {
    }

    public function getTmpFileDir(): string
    {
        return $this->tmpFileDir;
    }

    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }
}
