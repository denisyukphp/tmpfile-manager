<?php

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

interface TmpFileManagerInterface
{
    /**
     * @return TmpFileInterface
     *
     * @throws TmpFileIOException
     * @throws TmpFileCreateException
     */
    public function createTmpFile(): TmpFileInterface;

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
     * @param TmpFileInterface $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    /**
     * @throws TmpFileIOException
     */
    public function purge(): void;
}