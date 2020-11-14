<?php

namespace TmpFileManager\TmpFileHandler;

use TmpFile\TmpFileInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use TmpFileManager\TmpFileHandler\Exception\TmpFileIOException;

class TmpFileHandler implements TmpFileHandlerInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $dir
     * @param string $prefix
     *
     * @return string
     *
     * @throws TmpFileIOException
     */
    public function getTmpFileName(string $dir, string $prefix): string
    {
        try {
            return $this->filesystem->tempnam($dir, $prefix);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @return bool
     *
     * @throws TmpFileIOException
     */
    public function existsTmpFile(TmpFileInterface $tmpFile): bool
    {
        try {
            return $this->filesystem->exists($tmpFile);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }

    /**
     * @param TmpFileInterface $tmpFile
     *
     * @throws TmpFileIOException
     */
    public function removeTmpFile(TmpFileInterface $tmpFile): void
    {
        try {
            $this->filesystem->remove($tmpFile);
        } catch (IOException $e) {
            throw new TmpFileIOException(
                $e->getMessage()
            );
        }
    }
}
