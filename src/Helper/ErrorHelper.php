<?php
namespace Helper;

abstract class ErrorHelper
{
    const EMPTY_OR_ERROR_ROUTER = 'Non è stata settata alcuna rotta, o non sono stati settati i parametri obbligatori per ciascuna di essa.';
    const CONTROLLER_OR_ACTION_NOT_FOUND = 'Il Controller e la Action non risultano settati, o non sono stati matchati con la URI presente';
    const CONTROLLER_RETURN_EMPTY_ARRAY = 'Il controller ha restituito un array vuoto.';
}
