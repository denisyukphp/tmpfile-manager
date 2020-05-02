<?php

namespace TmpFileManager\DeferredPurgeHandler;

class DeferredPurgeListener
{
    public function __invoke(DeferredPurgeEvent $deferredPurgeEvent): void
    {
        $deferredPurgeHandler = $deferredPurgeEvent->getTmpFileManager()->getConfig()->getDeferredPurgeHandler();

        if (!$deferredPurgeHandler instanceof NullDeferredPurgeHandler) {
            $deferredPurgeHandler($deferredPurgeEvent->getTmpFileManager());
        }
    }
}