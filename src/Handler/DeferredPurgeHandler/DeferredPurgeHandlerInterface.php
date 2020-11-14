<?php

namespace TmpFileManager\Handler\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

interface DeferredPurgeHandlerInterface
{
    public function handle(TmpFileManagerInterface $tmpFileManager): void;
}
