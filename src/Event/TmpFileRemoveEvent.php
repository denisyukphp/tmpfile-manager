<?php

namespace TmpFileManager\Event;

use TmpFile\TmpFileInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class TmpFileRemoveEvent extends Event
{
    /**
     * @var TmpFileInterface
     */
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
