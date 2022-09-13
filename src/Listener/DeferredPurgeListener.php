<?php

declare(strict_types=1);

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

/**
 * @codeCoverageIgnore
 */
final class DeferredPurgeListener
{
    public function __invoke(TmpFileManagerStartEvent $event): void
    {
        if ($event->config->isDeferredPurge()) {
            $event->config->getDeferredPurgeHandler()->handle($event->tmpFileManager);
        }
    }
}
