<?php

namespace TmpFileManager\UnclosedResourcesHandler;

use TmpFileManager\PurgeEvent;

class UnclosedResourcesListener
{
    public function __invoke(PurgeEvent $purgeEvent): void
    {
        $config = $purgeEvent->getTmpFileManager()->getConfig();

        $container = $purgeEvent->getTmpFileManager()->getContainer();

        $handler = $config->getUnclosedResourcesHandler();

        if ($config->getUnclosedResourcesCheck()) {
            $handler->handle($container->getTmpFiles());
        }
    }
}