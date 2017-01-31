<?php
namespace Helper;

class ErrorHelper extends ErrorHelperAbstract
{
    public static function setError(string $error, $levelError)
    {
        return trigger_error($error, $levelError);
    }
}
