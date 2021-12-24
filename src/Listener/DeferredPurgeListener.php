<?php

declare(strict_types=1);

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

final class DeferredPurgeListener
{
    public function __invoke(TmpFileManagerStartEvent $event): void
    {
        if ($event->tmpFileManager->config->isDeferredPurge()) {
            $event->tmpFileManager->config->getDeferredPurgeHandler()->handle($event->tmpFileManager);
        }
    }
}
