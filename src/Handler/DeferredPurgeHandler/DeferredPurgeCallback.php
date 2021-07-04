<?php

namespace TmpFileManager\Handler\DeferredPurgeHandler;

use TmpFileManager\TmpFileManagerInterface;

final class DeferredPurgeCallback
{
    /**
     * @var TmpFileManagerInterface
     */
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
