<?php
namespace Helper;

class ErrorHelper
{
    public static function setError(string $error, $levelError)
    {
        return trigger_error($error, $levelError);
    }
}