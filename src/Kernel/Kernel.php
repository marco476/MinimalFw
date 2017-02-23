<?php
namespace Kernel;

use Kernel\Routing;
use Helper\ErrorHelper;
use Providers\ProvidersInterface;
use Providers\TemplateEngine\TemplateEngine;

class Kernel
{
    //Name of request URI.
    protected $requestURI;

    //List of all providers setted.
    protected $providers = array();

    //Kernel Costruct.
    public function __construct()
    {
        $this->requestURI = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    }

    //Find if URI is in routes setted.If true, execute action.
    public function start()
    {
        $Routing = new Routing();
        $Routing->setFromYml();

        foreach ($Routing->getRoutes() as $route) {
            if (preg_match($route['route'], $this->requestURI)) {
                $this->executeAction($route);
            }
        }
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

    //Set a provider.
    public function setProvider(ProvidersInterface $providerInstance, array $options)
    {
        if (empty($options) || empty($providerInstance->startProvide($options))) {
            return false;
        }

        $key = $providerInstance->getClassName();
        return $this->providers[$key] = $providerInstance;
    }

    //Get a provider indicated on key.
    public function getProvider($key)
    {
        return !empty($this->providers[$key]) ? $this->providers[$key] : false;
    }

    //Return a TemplateEngine istance for the action's controller
    protected function getTemplateEngine()
    {
        $templateEngine = $this->getProvider('TemplateEngine');

        return !empty($templateEngine) ? $templateEngine : $this->setProvider(new TemplateEngine(), array('name' => TemplateEngine::BASE));
    }
}
