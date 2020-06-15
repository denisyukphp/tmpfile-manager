<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\TmpFileManagerInterface;

interface DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManagerInterface $tmpFileManager): void;
}