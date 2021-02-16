<?php

namespace TmpFileManager\Tests;

use TmpFile\TmpFile;

class SplFileInfoBuilder
{
    /**
     * @var \SplFileInfo
     */
    private $splFileInfo;

    private function __construct()
    {
        $tmpFile = new TmpFile();

        $this->splFileInfo = new \SplFileInfo($tmpFile);
    }

    public static function create(): self
    {
        return new self();
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
