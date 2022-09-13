<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

final class DeferredPurgeCallback
{
    public function __construct(
        private readonly TmpFileManagerInterface $tmpFileManager,
    ) {
    }

    public function __invoke(): void
    {
        $this->tmpFileManager->purge();
    }
}
