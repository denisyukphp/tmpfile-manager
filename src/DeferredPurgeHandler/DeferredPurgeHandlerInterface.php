<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\TmpFileManager;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManager $tmpFileManager): void;
}