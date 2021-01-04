<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;

class SplFileInfoBuilder
{
    /**
     * @var \SplFileInfo
     */
    private $splFileInfo;

    public function __construct()
    {
        $this->splFileInfo = new \SplFileInfo(
            new TmpFile()
        );
    }

    public function addData(string $data): self
    {
        file_put_contents($this->splFileInfo, $data, FILE_APPEND);

        return $this;
    }

    public function build(): \SplFileInfo
    {
        return $this->splFileInfo;
    }
}
