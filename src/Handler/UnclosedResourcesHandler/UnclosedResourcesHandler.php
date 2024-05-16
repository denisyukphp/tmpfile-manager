<?php

declare(strict_types=1);

namespace TmpFileManager\Handler\UnclosedResourcesHandler;

use TmpFile\TmpFileInterface;

final class UnclosedResourcesHandler implements UnclosedResourcesHandlerInterface
{
    /**
     * @param TmpFileInterface[] $tmpFiles
     */
    public function handle(array $tmpFiles): void
    {
        if (0 === \count($tmpFiles)) {
            return; // @codeCoverageIgnore
        }

        $resources = get_resources('stream');
        $filenames = array_map(static fn (TmpFileInterface $tmpFile): string => $tmpFile->getFilename(), $tmpFiles);

        foreach ($resources as $resource) {
            if (!stream_is_local($resource)) {
                continue; // @codeCoverageIgnore
            }

            $metadata = stream_get_meta_data($resource);

            if (\in_array($metadata['uri'], $filenames, true)) {
                fclose($resource);
            }
        }
    }
}
