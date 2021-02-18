<?php

namespace TmpFileManager\Filesystem;

use TmpFileManager\TmpFile\TmpFileInterface;
use Symfony\Component\Filesystem\Filesystem as Fs;

class Filesystem implements FilesystemInterface
{
    /**
     * @var Fs
     */
    private $fs;

    public function __construct(Fs $fs)
    {
        $this->fs = $fs;
    }

    public static function create(): FilesystemInterface
    {
        $fs = new Fs();

        return new self($fs);
    }

    public function getTmpFileName(string $dir, string $prefix): string
    {
        return $this->fs->tempnam($dir, $prefix);
    }

    public function existsTmpFile(TmpFileInterface $tmpFile): bool
    {
        return $this->fs->exists($tmpFile);
    }

    public function copySplFileInfo(\SplFileInfo $splFileInfo, TmpFileInterface $tmpFile): void
    {
        $this->fs->copy($splFileInfo, $tmpFile, true);
    }

    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        $this->fs->remove($tmpFile);
    }
}
