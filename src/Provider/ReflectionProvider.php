<?php

namespace TmpFileManager\Provider;

use TmpFile\TmpFileInterface;

final class ReflectionProvider implements ProviderInterface
{
    public function getTmpFile(string $filename): TmpFileInterface
    {
        $reflection = new \ReflectionClass(TmpFile::class);

        /** @var TmpFile $tmpFile */
        $tmpFile = $reflection->newInstanceWithoutConstructor();

        $filenameProperty = $reflection->getProperty('filename');

        $filenameProperty->setAccessible(true);

        $filenameProperty->setValue($tmpFile, $filename);

        return $tmpFile;
    }
}
