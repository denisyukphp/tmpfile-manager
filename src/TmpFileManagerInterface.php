<?php

namespace TmpFileManager;

use TmpFileManager\TmpFile\TmpFileInterface;

interface TmpFileManagerInterface
{
    public function createTmpFile(): TmpFileInterface;

    public function createTmpFileContext(callable $callback): void;

    public function createTmpFileFromSplFileInfo(\SplFileInfo $splFileInfo): TmpFileInterface;

    public function createTmpFileFromUploadedFile(string $filename): TmpFileInterface;

    public function copyTmpFile(TmpFileInterface $tmpFile): TmpFileInterface;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    public function purge(): void;
}
