<?php
namespace Kernel;
use Helper\ErrorHelper;

class Kernel extends Core
{
    //Name of request URI.
    protected $requestURI;

    //Kernel Costruct
    public function __construct()
    {
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    //Find if URI is in routes setted.
    //If true, execute action.
    public function start()
    {
        if (empty($this->routes)) {
            ErrorHelper::setError(ErrorHelper::EMPTY_OR_ERROR_ROUTER, ErrorHelper::FATAL);
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['route'], $this->requestURI)) {
                $this->executeAction($route);
            }
        }
    }

    //Execute the action of route matched.
    protected function executeAction($route)
    {
        $controllerIstance = $this->getControllerIstance($route['controller']);
        $action = $route['action'];
        $params = !empty($route['params']) && is_array($route['params']) ? $route['params'] : [];

        $result = $controllerIstance->{$action}($params);

        !empty($result) ?
            $this->requireViews($result) :
                ErrorHelper::setError(ErrorHelper::CONTROLLER_RETURN_EMPTY_ARRAY, ErrorHelper::FATAL);
    }

     //Return an istance of controller $controllerName
    protected function getControllerIstance(string $controllerName)
    {
        $fullControllerName = '\\Controller\\' . $controllerName;

        return new $fullControllerName;
    }

    //Require views returned from action by FIFO running.
    protected function requireViews(array $viewsList)
    {
        foreach ($viewsList as $view) {
            require_once $this->viewsDirFromRoot . '/' . $view;
        }
    }
}
