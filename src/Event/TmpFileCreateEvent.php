<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFile\TmpFileInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class TmpFileCreateEvent extends Event
{
    public function __construct(
        public readonly TmpFileInterface $tmpFile,
    ) {
    }
}
