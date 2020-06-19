<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

use Bulletproof\TmpFileManager\StartEvent;

class DeferredPurgeListener
{
    public function __invoke(StartEvent $startEvent): void
    {
        $tmpFileManager = $startEvent->getTmpFileManager();

        $config = $tmpFileManager->getConfig();

        $handler = $config->getDeferredPurgeHandler();

        if ($config->getDeferredPurge()) {
            $handler($tmpFileManager);
        }
    }
}