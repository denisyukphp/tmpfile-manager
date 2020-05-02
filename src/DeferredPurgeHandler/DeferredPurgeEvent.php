<?php

namespace TmpFileManager\DeferredPurgeHandler;

use TmpFileManager\TmpFileManager;
use Symfony\Contracts\EventDispatcher\Event;

class DeferredPurgeEvent extends Event
{
    private $tmpFileManager;

    public function __construct(TmpFileManager $tmpFileManager)
    {
        $this->tmpFileManager = $tmpFileManager;
    }

    public function getTmpFileManager(): TmpFileManager
    {
        return $this->tmpFileManager;
    }
}