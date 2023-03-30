<?php

declare(strict_types=1);

namespace TmpFileManager\Config;

interface ConfigInterface
{
    public function getTmpFileDirectory(): string;

    public function getTmpFilePrefix(): string;
}
