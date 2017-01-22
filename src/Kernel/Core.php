<?php
namespace Kernel;

require_once __DIR__ . '/../Providers/Cache/FilesystemCache.php';
use Providers\Cache\FilesystemCache;

class Core
{
    //Nome della request URI.
    private $requestURI;
    
    //Lista di rotte da poter matchare.
    private $routes = [];

    //Nome della rotta matchata.
    private $route;

    //Nome del controller matchato.
    private $controller;

    //Nome della action del controller matchato.
    private $action;

    /* Array dei parametri che una rotta può passare all'action del
       controller associato. */
    private $params;

    //Costruttore
    public function __construct(bool $cache = false)
    {
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';

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
        if (!empty($this->routes)) {
            return;
        }

        foreach ($routes as $singleRoute) {
            if (!empty($singleRoute['route']) && !empty($singleRoute['controller']) && !empty($singleRoute['action'])) {
                $this->routes[] = $singleRoute;
            }
        }

        if (isset($this->filesystemCacheIstance)) {
            $this->filesystemCacheIstance->set('rotte', var_export($this->routes, true), 'data', 'LIST_ROUTES');
        }
    }

    /**
     * Cerca se la URI ricercata è tra le rotte previste.
     * In caso negativo, restituisce 404.
     */
    public function findRoute()
    {
        if (empty($this->routes)) {
            trigger_error('Non è stata settata alcuna rotta, o non sono stati settati i parametri obbligatori per ciascuna di essa.', E_USER_ERROR);
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['route'], $this->requestURI)) {
                if (isset($this->filesystemCacheIstance)) {
                    if ($this->filesystemCacheIstance->get($route['route'])) {
                        exit();
                    }
                }

                $this->route = $route['route'];
                $this->controller = $route['controller'];
                $this->action = $route['action'];
                $this->params = !empty($route['params']) ? $route['params'] : [];
                return true;
            }
        }

        return false;
    }

    /**
     * Richiama la action del controller corrispondente alla URI ricercata.
     * Il metodo deve essere richiamato dopo findRoute.
     */
    public function executeAction()
    {
        require_once(__DIR__ . '/../Controller/' . $this->controller . '.php');
        eval('$result = \Controller\\' . $this->controller . '::' . $this->action . '($this->params);');

        !empty($result) ?
            $this->resolveViewsAction($result) :
                trigger_error('Il controller ha restituito un array vuoto.', E_USER_ERROR);
    }

    private function resolveViewsAction(array $viewsList)
    {
        ob_start();

        foreach ($viewsList as $view) {
            require_once __DIR__ . '/../Views/' . $view;
        }
        
        if (isset($this->filesystemCacheIstance)) {
            $this->filesystemCacheIstance->set($this->route, ob_get_contents());
        }

        ob_end_flush();
    }

    /**
     * Restuisce il nome della Request URI
     */
    public function getRequestURI()
    {
        return !empty($this->requestURI) ? $this->requestURI : false;
    }

    /**
     * Restituisce il nome del controller
     */
    public function getController()
    {
        return !empty($this->controller) ? $this->controller : false;
    }

    /**
     * Restituisce il nome del controller
     */
    public function getParams()
    {
        return !empty($this->params) ? $this->params : false;
    }

    /**
     * Restituisce il nome della action
     */

    public function getAction()
    {
        return !empty($this->action) ? $this->action : false;
    }

    public function getRoute()
    {
        return !empty($this->route) ? $this->route : false;
    }

    /**
     * @return array
     */
    public function getAllRoutes(): array
    {
        return $this->routes;
    }
}
