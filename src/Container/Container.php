<?php

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

final class Container implements ContainerInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $splObjectStorage;

    public function __construct()
    {
        $this->splObjectStorage = new \SplObjectStorage();
    }

    public function addTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->splObjectStorage->attach($tmpFile);
    }

    public function hasTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->splObjectStorage->contains($tmpFile);
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->splObjectStorage->detach($tmpFile);
    }

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array
    {
        return iterator_to_array($this->splObjectStorage, false);
    }

    public function getTmpFilesCount(): int
    {
        return $this->splObjectStorage->count();
    }
}
