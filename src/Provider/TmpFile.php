<?php

namespace TmpFileManager\Provider;

use TmpFile\TmpFileInterface;

final class TmpFile implements TmpFileInterface
{
    /**
     * @var string
     */
    private $filename;

    private function __construct()
    {
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
