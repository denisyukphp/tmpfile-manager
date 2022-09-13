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
        public readonly TmpFileManagerInterface $tmpFileManager,
        public readonly ConfigInterface $config,
        public readonly ContainerInterface $container,
        public readonly FilesystemInterface $filesystem,
    ) {
    }
}
