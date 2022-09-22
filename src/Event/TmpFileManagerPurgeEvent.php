<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use Symfony\Contracts\EventDispatcher\Event;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;
use TmpFileManager\TmpFileManagerInterface;

final class TmpFileManagerPurgeEvent extends Event
{
    public function __construct(
        public TmpFileManagerInterface $tmpFileManager,
        public ConfigInterface $config,
        public ContainerInterface $container,
        public FilesystemInterface $filesystem,
    ) {
    }
}
