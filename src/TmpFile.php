<?php

declare(strict_types=1);

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

/**
 * @codeCoverageIgnore
 */
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

    public function __toString(): string
    {
        return $this->filename;
    }
}
