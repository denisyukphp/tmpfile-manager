<?php

namespace TmpFileManager\TmpFile;

final class TmpFile implements TmpFileInterface
{
    private $filename;

    private function __construct()
    {
    }

    public function __toString(): string
    {
        return $this->filename;
    }
}
