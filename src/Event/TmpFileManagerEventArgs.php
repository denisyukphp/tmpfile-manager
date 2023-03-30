<?php

namespace TmpFileManager\Event;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;

final class TmpFileManagerEventArgs
{
    private ConfigInterface $config;
    private ContainerInterface $container;
    private FilesystemInterface $filesystem;

    public function __construct(ConfigInterface $config, ContainerInterface $container, FilesystemInterface $filesystem)
    {
        $this->config = $config;
        $this->container = $container;
        $this->filesystem = $filesystem;
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
