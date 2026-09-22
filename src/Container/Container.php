<?php

declare(strict_types=1);

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

final class Container implements ContainerInterface
{
    /**
     * @var \SplObjectStorage<TmpFileInterface, null>
     */
    private \SplObjectStorage $tmpFiles;

    public function __construct()
    {
        $this->tmpFiles = new \SplObjectStorage();
    }

    #[\Override]
    public function addTmpFile(TmpFileInterface $tmpFile): void
    {
        if ($this->hasTmpFile($tmpFile)) {
            throw new \InvalidArgumentException(\sprintf('Temp file "%s" has been already added.', $tmpFile->getFilename()));
        }

        $this->tmpFiles->offsetSet($tmpFile);
    }

    #[\Override]
    public function hasTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->tmpFiles->offsetExists($tmpFile);
    }

    #[\Override]
    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        if (!$this->hasTmpFile($tmpFile)) {
            throw new \InvalidArgumentException(\sprintf('Temp file "%s" hasn\'t been added yet.', $tmpFile->getFilename()));
        }

        $this->tmpFiles->offsetUnset($tmpFile);
    }

    #[\Override]
    public function clearTmpFiles(): void
    {
        $this->tmpFiles->removeAll($this->tmpFiles);
    }

    /**
     * @return TmpFileInterface[]
     */
    #[\Override]
    public function getTmpFiles(): array
    {
        if ($this->isEmpty()) {
            return [];
        }

        return iterator_to_array($this->tmpFiles, false);
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    #[\Override]
    public function count(): int
    {
        return \count($this->tmpFiles);
    }
}
