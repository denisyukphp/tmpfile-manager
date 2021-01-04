<?php

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

interface TmpFileManagerInterface
{
    public function createTmpFile(): TmpFileInterface;

    public function createTmpFileContext(callable $callback): void;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    public function purge(): void;
}
