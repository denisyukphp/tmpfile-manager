<?php

declare(strict_types=1);

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

final class GarbageCollectionListener
{
    public function __invoke(TmpFileManagerStartEvent $event): void
    {
        if ($event->tmpFileManager->config->getGarbageCollectionProbability()) {
            $event->tmpFileManager->config->getGarbageCollectionHandler()->handle($event->tmpFileManager->config);
        }
    }
}
