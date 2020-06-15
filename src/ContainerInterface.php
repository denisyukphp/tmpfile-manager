<?php

namespace Bulletproof\TmpFileManager;

use Bulletproof\TmpFile\TmpFileInterface;

interface ContainerInterface
{
    public function addTmpFile(TmpFileInterface $tmpFile): void;

    public function hasTmpFile(TmpFileInterface $tmpFile): bool;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array;

    public function getTmpFilesCount(): int;
}