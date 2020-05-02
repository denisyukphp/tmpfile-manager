<?php

namespace TmpFileManager\CloseOpenedResourcesHandler;

class CloseOpenedResourceListener
{
    public function __invoke(CloseOpenedResourceEvent $closeOpenedResourceEvent): void
    {
        $closeOpenedResourcesHandler = $closeOpenedResourceEvent->getConfig()->getCloseOpenedResourcesHandler();

        if ($closeOpenedResourceEvent->getConfig()->getCheckUnclosedResources()) {
            $closeOpenedResourcesHandler($closeOpenedResourceEvent->getTmpFiles());
        }
    }
}