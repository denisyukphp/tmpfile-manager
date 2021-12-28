<?php

declare(strict_types=1);

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

final class TmpFile implements TmpFileInterface
{
    public function __construct(
        private string $filename,
    ) {
    }

    public function getFilename(): string
    {
        return $this->filename;
    }
}
