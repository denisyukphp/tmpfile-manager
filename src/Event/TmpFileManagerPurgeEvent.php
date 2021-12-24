<?php

declare(strict_types=1);

namespace TmpFileManager\Event;

use TmpFileManager\TmpFileManager;
use Symfony\Contracts\EventDispatcher\Event;

final class TmpFileManagerPurgeEvent extends Event
{
    public function __construct(
        public readonly TmpFileManager $tmpFileManager,
    ) {
    }
}
