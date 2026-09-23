<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

final readonly class Config implements ConfigInterface
{
    public function __construct(
        private string $tmpFileDir,
        private string $tmpFilePrefix,
    ) {
    }

    #[\Override]
    public function getTmpFileDir(): string
    {
        return $this->tmpFileDir;
    }

    #[\Override]
    public function getTmpFilePrefix(): string
    {
        return $this->tmpFilePrefix;
    }
}
