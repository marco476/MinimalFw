<?php
namespace Kernel;

use Providers\Cache\FilesystemCache;

abstract class Core
{
    //Lista di rotte da poter matchare.
    protected $routes = [];

    protected $viewsDirFromRoot;

    //Costruttore
    public function __construct(bool $cache)
    {
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

    //Ritorna tutte le rotte settate.
    public function getAllRoutes(): array
    {
        return $this->routes;
    }
}
