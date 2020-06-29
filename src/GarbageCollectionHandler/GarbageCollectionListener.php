<?php

namespace TmpFileManager\GarbageCollectionHandler;

use TmpFileManager\StartEvent;

class GarbageCollectionListener
{
    public function __invoke(StartEvent $startEvent): void
    {
        $config = $startEvent->getTmpFileManager()->getConfig();

        $handler = $config->getGarbageCollectionHandler();

        if ($config->getGarbageCollectionProbability()) {
            $handler->handle($config);
        }
    }
}