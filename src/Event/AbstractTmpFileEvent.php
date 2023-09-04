<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFile\TmpFileInterface;

abstract class AbstractTmpFileEvent
{
    public function __construct(
        private TmpFileInterface $tmpFile,
    ) {
    }

    public function getTmpFile(): TmpFileInterface
    {
        return $this->tmpFile;
    }
}
