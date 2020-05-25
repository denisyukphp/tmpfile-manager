<?php

namespace TmpFileManager\GarbageCollectionHandler;

class GarbageCollectionListener
{
    public function __invoke(GarbageCollectionEvent $garbageCollectionEvent): void
    {
        $config = $garbageCollectionEvent->getConfig();

        $garbageCollectionHandler = $config->getGarbageCollectionHandler();

        if ($config->getAutoRemove()) {
            $garbageCollectionHandler($config);
        }
    }
}