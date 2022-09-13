<?php

declare(strict_types=1);

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

/**
 * @codeCoverageIgnore
 */
final class GarbageCollectionListener
{
    public function __invoke(TmpFileManagerStartEvent $event): void
    {
        if ($event->config->isGarbageCollection()) {
            $event->config->getGarbageCollectionHandler()->handle($event->config);
        }
    }
}
