<?php

declare(strict_types=1);

namespace TmpFileManager\Filesystem;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use TmpFile\TmpFileInterface;

final class Filesystem implements FilesystemInterface
{
    private SymfonyFilesystem $symfonyFilesystem;

    public function __construct(?SymfonyFilesystem $symfonyFilesystem = null)
    {
        $this->symfonyFilesystem = $symfonyFilesystem ?? new SymfonyFilesystem();
    }

    public function createTmpFile(string $tmpFileDirectory, string $tmpFilePrefix): string
    {
        return $this->symfonyFilesystem->tempnam($tmpFileDirectory, $tmpFilePrefix);
    }

    public function existsTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->symfonyFilesystem->exists($tmpFile->getFilename());
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->symfonyFilesystem->remove($tmpFile->getFilename());
    }
}
