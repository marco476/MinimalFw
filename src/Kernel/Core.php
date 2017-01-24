<?php
namespace Kernel;

use Providers\Cache\FilesystemCache;

abstract class Core
{
    //Lista di rotte da poter matchare.
    protected $routes = [];

    //Elenco di chiavi settabili globalmente con setGlobal
    private $definableOptions = [
        'controllerDirFromRoot', //Cartella dei controller
        'viewsDirFromRoot', //Cartella delle views
    ];

    //Contiene il path dei controller
    protected $controllerDirFromRoot;

    //Contiene il path delle views
    protected $viewsDirFromRoot;

    //Costruttore
    public function __construct(bool $cache)
    {
        $this->controllerDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/src/Controller';
        $this->viewsDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/src/Views';

        if ($cache) {
            $this->filesystemCacheIstance = FilesystemCache::getIstance();
            if ($this->filesystemCacheIstance->get('rotte')) {
                $this->routes = LIST_ROUTES;
            }
        }
    }

    //Setta le rotte implementate.
    public function setRoutes(array $routes)
    {
        if (!empty($this->routes) || empty($routes)) {
            return;
        }

        foreach ($routes as $singleRoute) {
            if (!empty($singleRoute['route']) && !empty($singleRoute['controller']) && !empty($singleRoute['action'])) {
                $this->routes[] = $singleRoute;
            }
        }

        if (isset($this->filesystemCacheIstance)) {
            $this->filesystemCacheIstance->set('rotte', $this->routes, 'data', 'LIST_ROUTES');
        }
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

    //Ritorna tutte le rotte impostate.
    public function getAllRoutes(): array
    {
        return $this->routes;
    }

    //Restituisce la directory dei controller
    public function getControllerDir()
    {
        return !empty($this->controllerDirFromRoot) ? $this->controllerDirFromRoot : false;
    }

    //Restituisce la directory delle views
    public function getViewsDir()
    {
        return !empty($this->viewsDirFromRoot) ? $this->viewsDirFromRoot : false;
    }

    //Restituisce un array contenente tutte le possibili opzioni
    //settabili globalmente mediante setGlobal
    public function getDefinableOptions(): array
    {
        return $this->definableOptions;
    }
}
