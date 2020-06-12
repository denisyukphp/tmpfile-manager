<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

class DeferredPurgeCallback
{
    private $tmpFileManager;

    public function __construct(TmpFileManagerInterface $tmpFileManager)
    {
        $this->tmpFileManager = $tmpFileManager;
    }

    public function __invoke(): void
    {
        $this->tmpFileManager->purge();
    }
}