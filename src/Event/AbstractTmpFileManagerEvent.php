<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;

abstract class AbstractTmpFileManagerEvent
{
    private ConfigInterface $config;
    private ContainerInterface $container;
    private FilesystemInterface $filesystem;

    public function __construct(TmpFileManagerEventArgs $fileManagerEventArgs)
    {
        $this->config = $fileManagerEventArgs->getConfig();
        $this->container = $fileManagerEventArgs->getContainer();
        $this->filesystem = $fileManagerEventArgs->getFilesystem();
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
