<?php

namespace Sta\PhpSignalHandler\Exception;

class RequiredFunctionIsDisabled extends \Exception
{
    public static function create($functionName)
    {
        return new self(
            sprintf(
                'Function "%1$s" is disabled in php.ini. Remove "%1$s" from "disable_functions" directive. Read more at http://php.net/manual/en/ini.core.php#ini.disable-functions.',
                $functionName
            )
        );
    }
}
