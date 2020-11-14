<?php

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

class Container implements ContainerInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $tmpFiles;

    public function __construct()
    {
        $this->tmpFiles = new \SplObjectStorage();
    }

    public function addTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->tmpFiles->attach($tmpFile);
    }

    public function hasTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->tmpFiles->contains($tmpFile);
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->tmpFiles->detach($tmpFile);
    }

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array
    {
        return iterator_to_array($this->tmpFiles, false);
    }

    public function getTmpFilesCount(): int
    {
        return $this->tmpFiles->count();
    }
}
