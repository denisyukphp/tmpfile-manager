<?php

namespace Bulletproof\TmpFileManager;

use Symfony\Contracts\EventDispatcher\Event;

class StartEvent extends Event
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