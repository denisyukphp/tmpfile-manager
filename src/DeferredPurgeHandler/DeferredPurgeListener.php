<?php

namespace TmpFileManager\DeferredPurgeHandler;

class DeferredPurgeListener
{
    public function __invoke(DeferredPurgeEvent $deferredPurgeEvent): void
    {
        $config = $deferredPurgeEvent->getTmpFileManager()->getConfig();

        $deferredPurgeHandler = $config->getDeferredPurgeHandler();

        if ($config->getDeferredAutoPurge()) {
            $deferredPurgeHandler($deferredPurgeEvent->getTmpFileManager());
        }
    }
}