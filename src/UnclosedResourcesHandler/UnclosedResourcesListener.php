<?php

namespace Bulletproof\TmpFileManager\UnclosedResourcesHandler;

class UnclosedResourcesListener
{
    public function __invoke(UnclosedResourcesEvent $unclosedResourcesEvent): void
    {
        $config = $unclosedResourcesEvent->getConfig();

        $unclosedResourcesHandler = $config->getUnclosedResourcesHandler();

        if ($config->getUnclosedResourcesCheck()) {
            $unclosedResourcesHandler($unclosedResourcesEvent->getTmpFiles());
        }
    }
}