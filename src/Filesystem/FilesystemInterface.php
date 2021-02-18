<?php

namespace TmpFileManager\Filesystem;

use TmpFileManager\TmpFile\TmpFileInterface;

interface FilesystemInterface
{
    public function getTmpFileName(string $dir, string $prefix): string;

    public function existsTmpFile(TmpFileInterface $tmpFile): bool;

    public function copySplFileInfo(\SplFileInfo $splFileInfo, TmpFileInterface $tmpFile): void;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;
}
