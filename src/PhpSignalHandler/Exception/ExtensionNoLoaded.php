<?php

namespace Sta\PhpSignalHandler\Exception;

class ExtensionNoLoaded extends \Exception
{
    public static function create($extensionName)
    {
        return new self(sprintf('Extension "%s" is not loaded.', $extensionName));
    }
}
