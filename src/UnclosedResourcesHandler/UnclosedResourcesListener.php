<?php

namespace Bulletproof\TmpFileManager\UnclosedResourcesHandler;

use Bulletproof\TmpFileManager\PurgeEvent;

class UnclosedResourcesListener
{
    public function __invoke(PurgeEvent $purgeEvent): void
    {
        $config = $purgeEvent->getTmpFileManager()->getConfig();

        $container = $purgeEvent->getTmpFileManager()->getContainer();

        $handler = $config->getUnclosedResourcesHandler();

        if ($config->getUnclosedResourcesCheck()) {
            $handler($container->getTmpFiles());
        }
    }
}