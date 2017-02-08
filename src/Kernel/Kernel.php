<?php
namespace Kernel;
use Helper\ErrorHelper;
use Helper\TemplateAbstract;

class Kernel extends Core
{
    //Name of request URI.
    protected $requestURI;

    //Istance of template engine (if used)
    protected $templateEngine;

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

    //Set an istance of template engine passed as argument.
    public function setTemplateEngine(string $engine, array $options = [])
    {
        switch ($engine) {
            case TemplateAbstract::TWIG:
                $istance = new \Providers\TemplateEngine\Twig();
                break;
            case TemplateAbstract::SMARTY:
                $istance = new \Providers\TemplateEngine\Smarty();
                break;
            default:
                $istance = null;
                break;
        }

        return $this->templateEngine = $istance;
    }
}
