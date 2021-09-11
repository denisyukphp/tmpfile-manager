<?php

namespace TmpFileManager\Provider;

use TmpFile\TmpFileInterface;

final class ReflectionProvider implements ProviderInterface
{
    /**
     * @param string $filename
     *
     * @return TmpFileInterface
     *
     * @throws \LogicException
     * @throws \ReflectionException
     */
    public function getTmpFile(string $filename): TmpFileInterface
    {
        if (!is_file($filename)) {
            throw new \LogicException(
                sprintf('The "%s" is not a file', $filename)
            );
        }

        $reflectionClass = new \ReflectionClass(TmpFile::class);

        /** @var TmpFile $tmpFile */
        $tmpFile = $reflectionClass->newInstanceWithoutConstructor();

        $filenameProperty = $reflectionClass->getProperty('filename');
        $filenameProperty->setAccessible(true);
        $filenameProperty->setValue($tmpFile, $filename);

        return $tmpFile;
    }
}
