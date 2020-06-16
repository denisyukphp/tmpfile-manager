<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\TmpFileManagerInterface;

class DeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManagerInterface $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}