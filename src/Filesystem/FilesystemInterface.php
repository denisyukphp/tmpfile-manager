<?php

declare(strict_types=1);

namespace TmpFileManager\Filesystem;

use TmpFile\TmpFileInterface;

interface FilesystemInterface
{
    public function createTmpFile(string $tmpFileDir, string $tmpFilePrefix): TmpFileInterface;

    public function existsTmpFile(TmpFileInterface $tmpFile): bool;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;
}
