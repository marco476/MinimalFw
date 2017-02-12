<?php
namespace Kernel;

use Helper\ErrorHelper;
use Providers\TemplateEngine\TemplateEngine;

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
        $controllerInstance = $this->getControllerInstance($route['controller']);
        $action = $route['action'];
        $params = !empty($route['params']) && is_array($route['params']) ? $route['params'] : [];

        $templateEngine = $this->getEngineForViews();
        return $controllerInstance->{$action}($params, $templateEngine);
    }

     //Return an instance of controller $controllerName
    protected function getControllerInstance(string $controllerName)
    {
        $fullControllerName = '\\Controller\\' . $controllerName;

        return new $fullControllerName;
    }

    //Return a TemplateEngine istance for the action's controller
    protected function getEngineForViews()
    {
        $templateEngine = $this->getProvider('TemplateEngine');

        return !empty($templateEngine) ? $templateEngine : $this->setProvider(new TemplateEngine(), array('name' => TemplateEngine::BASE));
    }
}
