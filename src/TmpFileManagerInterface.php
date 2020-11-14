<?php

namespace TmpFileManager;

use TmpFile\TmpFileInterface;
use TmpFileManager\Exception\TmpFileCreateException;
use TmpFileManager\Exception\TmpFileContextCallbackException;

interface TmpFileManagerInterface
{
    /**
     * @return TmpFileInterface
     *
     * @throws TmpFileCreateException
     */
    public function createTmpFile(): TmpFileInterface;

    /**
     * @param callable $callback
     *
     * @return mixed
     *
     * @throws TmpFileContextCallbackException
     */
    public function createTmpFileContext(callable $callback);

    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    public function purge(): void;
}
