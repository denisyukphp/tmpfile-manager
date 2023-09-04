<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;

abstract class AbstractTmpFileManagerEvent
{
    public function __construct(
        private TmpFileManagerEventArgs $fileManagerEventArgs,
    ) {
    }

    public function getConfig(): ConfigInterface
    {
        return $this->fileManagerEventArgs->getConfig();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->fileManagerEventArgs->getContainer();
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->fileManagerEventArgs->getFilesystem();
    }
}
