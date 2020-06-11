<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManagerInterface $tmpFileManager): void;
}