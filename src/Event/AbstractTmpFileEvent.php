<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFile\TmpFileInterface;

abstract class AbstractTmpFileEvent
{
    private TmpFileInterface $tmpFile;

    public function __construct(TmpFileInterface $tmpFile)
    {
        $this->tmpFile = $tmpFile;
    }

    public function getTmpFile(): TmpFileInterface
    {
        return $this->tmpFile;
    }
}
