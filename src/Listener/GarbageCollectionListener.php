<?php

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerStartEvent;

final class GarbageCollectionListener
{
    public function __invoke(TmpFileManagerStartEvent $startEvent): void
    {
        $tmpFileManager = $startEvent->getTmpFileManager();

        $config = $tmpFileManager->getConfig();

        $handler = $config->getGarbageCollectionHandler();

        if ($config->getGarbageCollectionProbability()) {
            $handler->handle($config);
        }
    }
}
