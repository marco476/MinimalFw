<?php
namespace Kernel;

use Helper\ErrorHelper;

class Kernel extends Core
{
    //Nome della request URI.
    protected $requestURI;

    //Nome della rotta matchata.
    protected $route;

    //Istanza del controller matchato.
    protected $controller;

    //Nome della action del controller matchato.
    protected $action;

    //Array dei parametri per la action
    protected $params;

    //Costruttore
    public function __construct(bool $cache = false)
    {
        parent::__construct($cache); //Richiamo prima il costruttore padre
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

     //Cerca se la URI ricercata è tra le rotte previste.
     //Restituisce true nel caso la rotta sia stata trovata. False al contrario.
    public function findRoute()
    {
        if (empty($this->routes)) {
            trigger_error(ErrorHelper::EMPTY_OR_ERROR_ROUTER, E_USER_ERROR);
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['route'], $this->requestURI)) {
                if (isset($this->filesystemCacheIstance)) {
                    if ($this->filesystemCacheIstance->get($route['route'])) {
                        exit();
                    }
                }

                $this->route = $route['route'];
                $this->controller = '\\Controller\\' . $route['controller'];
                $this->action = $route['action'];
                $this->params = !empty($route['params']) && is_array($route['params']) ? $route['params'] : [];
                return true;
            }
        }

        return false;
    }

     //Richiama la action del controller corrispondente alla URI ricercata.
    public function executeAction()
    {
        if (empty($this->controller) || empty($this->action)) {
            trigger_error(ErrorHelper::CONTROLLER_OR_ACTION_NOT_FOUND, E_USER_ERROR);
        }

        $controllerIstance = new $this->controller;
        $result = $controllerIstance->{$this->action}($this->params);

        !empty($result) ?
            $this->resolveViewsAction($result) :
                trigger_error(ErrorHelper::CONTROLLER_RETURN_EMPTY_ARRAY, E_USER_ERROR);
    }

    //Richiama le Views restituite dalla action.
    //Esso vengono include mediante una gestione FIFO.
    protected function resolveViewsAction(array $viewsList)
    {
        ob_start();

        foreach ($viewsList as $view) {
            require_once $this->viewsDirFromRoot . '/' . $view;
        }
        
        if (isset($this->filesystemCacheIstance)) {
            $this->filesystemCacheIstance->set($this->route, ob_get_contents());
        }

        ob_end_flush();
    }

    //Restuisce il nome della Request URI.
    public function getRequestURI(): string
    {
        return $this->requestURI;
    }

    //Restituisce il nome del controller della rotta matchata.
    public function getController()
    {
        return !empty($this->controller) ? $this->controller : false;
    }

    //Restituisce il nome dei parametri della rotta matchata.
    public function getParams()
    {
        return !empty($this->params) ? $this->params : false;
    }

    //Restituisce il nome della action della rotta matchata.
    public function getAction()
    {
        return !empty($this->action) ? $this->action : false;
    }

    //Restituisce la RE che definisce della rotta matchata.
    public function getRoute()
    {
        return !empty($this->route) ? $this->route : false;
    }
}
