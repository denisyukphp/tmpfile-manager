<?php

declare(strict_types=1);

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

final class Container implements ContainerInterface
{
    private \SplObjectStorage $storage;

    public function __construct()
    {
        $this->storage = new \SplObjectStorage();
    }

    public function addTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->storage->attach($tmpFile);
    }

    public function hasTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->storage->contains($tmpFile);
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->storage->detach($tmpFile);
    }

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array
    {
        return iterator_to_array($this->storage, false);
    }

    public function getTmpFilesCount(): int
    {
        return $this->storage->count();
    }
}
