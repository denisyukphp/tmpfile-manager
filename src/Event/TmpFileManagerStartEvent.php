<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFileManager\TmpFileManagerInterface;
use TmpFileManager\Config\ConfigInterface;
use TmpFileManager\Container\ContainerInterface;
use TmpFileManager\Filesystem\FilesystemInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class TmpFileManagerStartEvent extends Event
{
    public function __construct(
        public readonly TmpFileManagerInterface $tmpFileManager,
        public readonly ConfigInterface $config,
        public readonly ContainerInterface $container,
        public readonly FilesystemInterface $filesystem,
    ) {
    }
}
