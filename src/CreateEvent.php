<?php

namespace Bulletproof\TmpFileManager;

use Bulletproof\TmpFile\TmpFileInterface;
use Symfony\Contracts\EventDispatcher\Event;

class CreateEvent extends Event
{
    private $tmpFile;

    public function __construct(TmpFileInterface $tmpFile)
    {
        $this->tmpFile = $tmpFile;
    }

    public function getTmpFile(): TmpFileInterface
    {
        return $this->tmpFile;
    }
}