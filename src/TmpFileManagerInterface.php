<?php

namespace TmpFileManager;

use TmpFile\TmpFile;

interface TmpFileManagerInterface
{
    /**
     * @return TmpFile
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     */
    public function createTmpFile(): TmpFile;

    /**
     * @param callable $callback
     *
     * @return mixed
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     * @throws TmpFileContextCallbackException
     */
    public function createTmpFileContext(callable $callback);

    /**
     * @param TmpFile $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFile $tmpFile): void;

    /**
     * @throws TmpFileIOException
     */
    public function purge(): void;
}