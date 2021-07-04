<?php

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

final class DeferredPurgeListener
{
    public function __invoke(TmpFileManagerStartEvent $startEvent): void
    {
        $tmpFileManager = $startEvent->getTmpFileManager();

        $config = $tmpFileManager->getConfig();

        $handler = $config->getDeferredPurgeHandler();

        if ($config->getDeferredPurge()) {
            $handler->handle($tmpFileManager);
        }
    }
}
