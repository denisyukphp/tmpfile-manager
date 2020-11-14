<?php

namespace TmpFileManager\Event;

use TmpFileManager\TmpFileManager;
use Symfony\Contracts\EventDispatcher\Event;

class TmpFileManagerStartEvent extends Event
{
    /**
     * @var TmpFileManager
     */
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
