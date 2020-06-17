<?php

namespace Bulletproof\TmpFileManager\DeferredPurgeHandler;

class DeferredPurgeListener
{
    public function __invoke(DeferredPurgeEvent $deferredPurgeEvent): void
    {
        $tmpFileManager = $deferredPurgeEvent->getTmpFileManager();

        $config = $deferredPurgeEvent->getConfig();

        $deferredPurgeHandler = $config->getDeferredPurgeHandler();

        if ($config->getDeferredPurge()) {
            $deferredPurgeHandler($tmpFileManager);
        }
    }
}