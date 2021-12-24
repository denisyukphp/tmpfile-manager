<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

final class DeferredPurgeHandler implements DeferredPurgeHandlerInterface
{
    public function handle(TmpFileManagerInterface $tmpFileManager): void
    {
        register_shutdown_function(new DeferredPurgeCallback($tmpFileManager));
    }
}
