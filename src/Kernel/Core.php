<?php
namespace Kernel;

abstract class Core
{
    //Elenco di chiavi settabili globalmente con setGlobal
    private $definableOptions = [
        'controllerDirFromRoot', //Cartella dei controller
    ];

    //Contiene il path dei controller
    protected $controllerDirFromRoot;

    //Costruttore
    public function __construct()
    {
        $this->controllerDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/src/Controller';
    }

    //Setta le variabili globali in base all'array passato come argomento
    //alla seguente funzione, il quale deve contenere le chiavi
    //presenti in $definableOptions
    public function setGlobal(array $options)
    {
        if (empty($options)) {
            return;
        }

        foreach ($options as $key => $option) {
            if (in_array($key, $this->definableOptions)) {
                $this->$key = $option;
            }
        }
    }
}
