<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use Symfony\Contracts\EventDispatcher\Event;
use TmpFile\TmpFileInterface;

final class TmpFileRemoveEvent extends Event
{
    public function __construct(
        public readonly TmpFileInterface $tmpFile,
    ) {
    }
}
