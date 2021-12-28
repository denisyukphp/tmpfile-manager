<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFileManager\Container\ContainerInterface;
use TmpFile\TmpFileInterface;

final class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    public function handle(ContainerInterface $container): void
    {
        $filenames = array_map(function (TmpFileInterface $tmpFile): string {
            return $tmpFile->getFilename();
        }, $container->getTmpFiles());

        foreach (get_resources('stream') as $resource) {
            if (!stream_is_local($resource)) {
                continue;
            }

            $metadata = stream_get_meta_data($resource);

            if (in_array($metadata['uri'], $filenames, true)) {
                fclose($resource);
            }
        }
    }
}
