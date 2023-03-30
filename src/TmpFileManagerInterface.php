<?php

declare(strict_types=1);

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

interface TmpFileManagerInterface
{
    public function create(): TmpFileInterface;

    public function load(\SplFileInfo ...$files): void;

    public function isolate(callable $callback): void;

    public function remove(TmpFileInterface $tmpFile): void;

    public function purge(): void;
}
