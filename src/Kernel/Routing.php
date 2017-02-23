<?php
namespace Kernel;

use Helper\ErrorHelper;

class Routing
{
    //Path of config directory.
    protected $configPath;

    //Path of routes YML file.
    protected $routesYmlFile;

    //List of all routes that can match with URI.
    protected $routes = array();

    //Routing constructor.
    public function __construct($configPath = false, $nameFile = false)
    {
        $this->configPath = !empty($configPath) ? $configPath : $_SERVER["DOCUMENT_ROOT"] . '/../config';
        $this->routesYmlFile = !empty($nameFile) ? $nameFile : $this->configPath . '/routes.yml';
    }

    //Set routes from a YML configuration file.
    public function setFromYml()
    {
        if (!empty($this->routes)) {
            return false;
        }

        $routesFromYml = yaml_parse_file($this->routesYmlFile);

        empty($routesFromYml) ?
            ErrorHelper::setError(ErrorHelper::EMPTY_OR_ERROR_ROUTER, ErrorHelper::FATAL) :
                $this->setRoutes($routesFromYml);
    }

    //Set routes into $this->routes variable from $routes array.
    public function setRoutes(array $routes)
    {
        if (empty($routes) || !empty($this->routes)) {
            return false;
        }

        foreach ($routes as $route) {
            if (!empty($route['route']) && !empty($route['controller']) && !empty($route['action'])) {
                $this->routes[] = $route;
            }
        }
    }

    //Return all routes setted.
    public function getRoutes()
    {
        return $this->routes;
    }
}
