<?php

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFileManager\Container\ContainerInterface;

final class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    public function handle(ContainerInterface $container): void
    {
        $tmpFiles = $container->getTmpFiles();

        foreach (get_resources('stream') as $resource) {
            if (!stream_is_local($resource)) {
                continue;
            }

            $metadata = stream_get_meta_data($resource);

            if (in_array($metadata['uri'], $tmpFiles)) {
                fclose($resource);
            }
        }
    }
}
