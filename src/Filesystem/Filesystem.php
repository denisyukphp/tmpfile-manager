<?php

declare(strict_types=1);

namespace TmpFileManager\Filesystem;

use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;
use TmpFile\TmpFileInterface;
use TmpFileManager\TmpFile;

final class Filesystem implements FilesystemInterface
{
    public function __construct(
        private SymfonyFilesystem $symfonyFilesystem,
    ) {
    }

    public function createTmpFile(string $tmpFileDir, string $tmpFilePrefix): TmpFileInterface
    {
        $filename = $this->symfonyFilesystem->tempnam($tmpFileDir, $tmpFilePrefix);

        return new TmpFile($filename);
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
