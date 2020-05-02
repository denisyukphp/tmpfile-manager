<?php

namespace TmpFileManager\GarbageCollectionHandler;

class GarbageCollectionListener
{
    public function __invoke(GarbageCollectionEvent $garbageCollectionEvent): void
    {
        $garbageCollectionHandler = $garbageCollectionEvent->getConfig()->getGarbageCollectionHandler();

        if ($garbageCollectionEvent->getConfig()->getGarbageCollectionProbability()) {
            $garbageCollectionHandler($garbageCollectionEvent->getConfig());
        }
    }
}