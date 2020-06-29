<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;

interface DeferredPurgeHandlerInterface
{
    public function handle(TmpFileManager $tmpFileManager): void;
}