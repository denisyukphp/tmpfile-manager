<?php

declare(strict_types=1);

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

final class Container implements ContainerInterface
{
    private \SplObjectStorage $tmpFiles;

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
     * @psalm-suppress MoreSpecificReturnType
     * @psalm-suppress LessSpecificReturnStatement
     *
     * @return list<TmpFileInterface>
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
