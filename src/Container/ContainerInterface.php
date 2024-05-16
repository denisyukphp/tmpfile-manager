<?php

declare(strict_types=1);

namespace TmpFileManager\Container;

use TmpFile\TmpFileInterface;

interface ContainerInterface extends \Countable
{
    public function addTmpFile(TmpFileInterface $tmpFile): void;

    public function hasTmpFile(TmpFileInterface $tmpFile): bool;

    public function removeTmpFile(TmpFileInterface $tmpFile): void;

    public function clearTmpFiles(): void;

    /**
     * @return TmpFileInterface[]
     */
    public function getTmpFiles(): array;

    public function isEmpty(): bool;
}
