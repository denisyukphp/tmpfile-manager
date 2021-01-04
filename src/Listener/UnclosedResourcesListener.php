<?php

namespace TmpFileManager\Listener;

use TmpFileManager\Event\TmpFileManagerPurgeEvent;

class UnclosedResourcesListener
{
    public function __invoke(TmpFileManagerPurgeEvent $purgeEvent): void
    {
        $tmpFileManager = $purgeEvent->getTmpFileManager();

        $config = $tmpFileManager->getConfig();

        $container = $tmpFileManager->getContainer();

        $handler = $config->getUnclosedResourcesHandler();

        if ($config->getUnclosedResourcesCheck()) {
            $handler->handle($container);
        }
    }
}
