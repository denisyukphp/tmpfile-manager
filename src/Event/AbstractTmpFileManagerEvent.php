<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;

/**
 * @codeCoverageIgnore
 */
abstract class AbstractTmpFileManagerEvent
{
    public function __construct(
        private TmpFileManagerEventArgs $args,
    ) {
    }

    public function getConfig(): ConfigInterface
    {
        return $this->args->getConfig();
    }

    public function getContainer(): ContainerInterface
    {
        return $this->args->getContainer();
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->args->getFilesystem();
    }
}
