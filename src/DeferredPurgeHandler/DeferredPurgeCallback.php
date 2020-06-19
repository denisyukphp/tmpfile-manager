<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\TmpFileManager;

class DeferredPurgeCallback
{
    private $tmpFileManager;

    public function __construct(TmpFileManager $tmpFileManager)
    {
        $this->tmpFileManager = $tmpFileManager;
    }

    public function __invoke(): void
    {
        $this->tmpFileManager->purge();
    }
}