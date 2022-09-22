<?php

declare(strict_types=1);

namespace TmpFileManager\Filesystem;

use Symfony\Component\Filesystem\Filesystem as Fs;
use TmpFile\TmpFileInterface;

final class Filesystem implements FilesystemInterface
{
    private Fs $fs;

    public function __construct(?Fs $fs = null)
    {
        $this->fs = $fs ?? new Fs();
    }

    public function getTmpFileName(string $dir, string $prefix): string
    {
        return $this->fs->tempnam($dir, $prefix);
    }

    public function existsTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->fs->exists($tmpFile->getFilename());
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->fs->remove($tmpFile->getFilename());
    }
}
