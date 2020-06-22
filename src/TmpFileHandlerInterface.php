<?php

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

interface TmpFileHandlerInterface
{
    /**
     * @param string $dir
     * @param string $prefix
     *
     * @return string
     *
     * @throws TmpFileIOException
     */
    public function getTmpFileName(string $dir, string $prefix): string;

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @return bool
     *
     * @throws TmpFileIOException
     */
    public function existsTmpFile(TmpFileInterface $tmpFile): bool;

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFileInterface $tmpFile): void;
}