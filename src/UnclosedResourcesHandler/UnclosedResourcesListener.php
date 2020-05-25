<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

class UnclosedResourcesListener
{
    public function __invoke(UnclosedResourcesEvent $unclosedResourcesEvent): void
    {
        $config = $unclosedResourcesEvent->getConfig();

        $unclosedResourcesHandler = $config->getUnclosedResourcesHandler();

        if ($config->isCheckUnclosedResources()) {
            $unclosedResourcesHandler($unclosedResourcesEvent->getTmpFiles());
        }
    }
}