<?php

declare(strict_types=1);

namespace TmpFileManager\Filesystem;

use TmpFile\TmpFileInterface;

interface FilesystemInterface
{
    public function getTmpFileName(string $dir, string $prefix): string;

    public function existsTmpFile(TmpFileInterface $tmpFile): bool;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;
}
