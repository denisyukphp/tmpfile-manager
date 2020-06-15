<?php

namespace Bulletproof\TmpFileManager\GarbageCollectionHandler;

class GarbageCollectionListener
{
    public function __invoke(GarbageCollectionEvent $garbageCollectionEvent): void
    {
        $config = $garbageCollectionEvent->getConfig();

        $garbageCollectionHandler = $config->getGarbageCollectionHandler();

        if ($config->getGarbageCollectionProbability()) {
            $garbageCollectionHandler($config);
        }
    }
}