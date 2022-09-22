<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use Symfony\Contracts\EventDispatcher\Event;
use TmpFile\TmpFileInterface;

final class TmpFileCreateEvent extends Event
{
    public function __construct(
        public TmpFileInterface $tmpFile,
    ) {
    }
}
