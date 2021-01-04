<?php

namespace TmpFileManager\TmpFileHandler;

use TmpFile\TmpFileInterface;

interface TmpFileHandlerInterface
{
    public function getTmpFileName(string $dir, string $prefix): string;

    public function existsTmpFile(TmpFileInterface $tmpFile): bool;

    public function copySplFileInfo(\SplFileInfo $splFileInfo, TmpFileInterface $tmpFile): void;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;
}
