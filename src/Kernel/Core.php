<?php
namespace Kernel;

use Kernel\CoreInterface;

abstract class Core implements CoreInterface
{
    //List of all routes that can match with URI.
    protected $routes = [];

    protected $viewsDirFromRoot;

    //Core Costruct
    public function __construct()
    {
         $this->viewsDirFromRoot = $_SERVER["DOCUMENT_ROOT"] . '/src/Views';
    }

    //Setta le rotte implementate.
    public function setRoutes(array $routes)
    {
        foreach ($routes as $singleRoute) {
            if (!empty($singleRoute['route']) && !empty($singleRoute['controller']) && !empty($singleRoute['action'])) {
                $this->routes[] = $singleRoute;
            }
        }
    }

    //Return an istance of controller $controllerName
    protected function getControllerIstance(string $controllerName)
    {
        $fullControllerName = '\\Controller\\' . $controllerName;

        return new $fullControllerName;
    }
}
