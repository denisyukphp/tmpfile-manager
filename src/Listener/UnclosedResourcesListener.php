<?php

declare(strict_types=1);

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerPurgeEvent;

/**
 * @codeCoverageIgnore
 */
final class UnclosedResourcesListener
{
    public function __invoke(TmpFileManagerPurgeEvent $event): void
    {
        if ($event->config->isUnclosedResourcesCheck()) {
            $event->config->getUnclosedResourcesHandler()->handle($event->container);
        }
    }
}
