<?php

declare(strict_types=1);

namespace TmpFileManager;

use TmpFile\TmpFileInterface;

/**
 * @codeCoverageIgnore
 */
final readonly class TmpFile implements \Stringable, TmpFileInterface
{
    public function __construct(
        private string $filename,
    ) {
    }

    #[\Override]
    public function getFilename(): string
    {
        return $this->filename;
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->filename;
    }
}
