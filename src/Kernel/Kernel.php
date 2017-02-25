<?php
namespace Kernel;

use Routing\Routing;
use Providers\AdminProviders;
use Providers\TemplateEngine\TemplateEngine;

class Kernel
{
    //MinimalFw start elaboration!
    public function start()
    {
        $Routing = new Routing();
        $routeMatched = $Routing->setRoutesFromYml($_SERVER["DOCUMENT_ROOT"] . '/../config', 'routes.yml')->matchRoute();

        if (empty($routeMatched)) {
            trigger_error("The URI not matched with routes setted.", E_USER_ERROR);
        }

        if (empty($routeMatched['controller']) || empty($routeMatched['action'])) {
            trigger_error("The URI matched with a route setted, but route not have setted a Controller or Action key.", E_USER_ERROR);
        }

        return $this->executeAction($routeMatched);
    }

    //Execute the action of route matched.
    protected function executeAction($route)
    {
        $controllerName = '\\Controller\\' . $route['controller'];
        $controller = new $controllerName;
        $action = $route['action'];
        $params = !empty($route['params']) && is_array($route['params']) ? $route['params'] : [];

        $templateEngine = $this->getTemplateEngine();
        return $controller->{$action}($params, $templateEngine->getEngine());
    }

    //Return a TemplateEngine istance for the action's controller
    protected function getTemplateEngine()
    {
        $templateEngine = AdminProviders::getProvider('TemplateEngine');

        return !empty($templateEngine) ? $templateEngine : AdminProviders::setProvider(new TemplateEngine(), array('name' => TemplateEngine::BASE));
    }
}
