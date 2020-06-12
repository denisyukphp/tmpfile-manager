<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

class DefaultDeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function __invoke(TmpFileManagerInterface $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}