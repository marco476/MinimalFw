<?php
namespace Helper;

class ErrorHelper
{
    const WARNING = E_USER_WARNING;
    const FATAL = E_USER_ERROR;

    const EMPTY_OR_ERROR_ROUTER = 'Non è stata settata alcuna rotta, o non sono stati settati i parametri obbligatori per ciascuna di essa.';
    const DIR_VIEWS_NOT_CREATE = "I can't create the directory Views in /src/Views. Why don't you try to create manually?";

    public static function setError(string $error, $levelError)
    {
        return trigger_error($error, $levelError);
    }
}