<?php

namespace TmpFileManager\Handler\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

class DeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function handle(TmpFileManagerInterface $tmpFileManager): void
    {
        $callback = new DeferredPurgeCallback($tmpFileManager);

        register_shutdown_function($callback);
    }
}
