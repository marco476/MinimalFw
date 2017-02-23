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
    public function __construct()
    {
        $this->configPath = $_SERVER["DOCUMENT_ROOT"] . '/../config';
        $this->routesYmlFile = $this->configPath . '/routes.yml';
    }

    //Read routes from a YML configuration file and
    //set in $routes protected array.
    public function readFromYml()
    {
        $routesFromYml = yaml_parse_file($this->routesYmlFile);

        empty($routesFromYml) ?
            ErrorHelper::setError(ErrorHelper::EMPTY_OR_ERROR_ROUTER, ErrorHelper::FATAL) :
                $this->setRoutes($routesFromYml);
    }

    public function getRoutes(){
        return $this->routes;
    }

    //Set routes.
    protected function setRoutes(array $routesFromYml)
    {
        foreach ($routesFromYml as $route) {
            if (!empty($route['route']) && !empty($route['controller']) && !empty($route['action'])) {
                $this->routes[] = $route;
            }
        }
    }
}
