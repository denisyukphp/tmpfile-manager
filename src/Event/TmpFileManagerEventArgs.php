<?php

namespace TmpFileManager\Event;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;

/**
 * @codeCoverageIgnore
 */
final class TmpFileManagerEventArgs
{
    public function __construct(
        private ConfigInterface $config,
        private ContainerInterface $container,
        private FilesystemInterface $filesystem,
    ) {
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getFilesystem(): FilesystemInterface
    {
        return $this->filesystem;
    }
}
