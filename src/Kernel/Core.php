<?php
namespace Kernel;

use Providers\ProvidersInterface;

abstract class Core
{
    //List of all routes that can match with URI.
    protected $routes = array();

    //List of all providers setted.
    protected $providers = array();

    //Set routes.
    public function setRoutes(array $routes)
    {
        foreach ($routes as $singleRoute) {
            if (!empty($singleRoute['route']) && !empty($singleRoute['controller']) && !empty($singleRoute['action'])) {
                $this->routes[] = $singleRoute;
            }
        }
    }

    //Set a provider.
    public function setProvider(ProvidersInterface $providerInstance, array $options)
    {
        if (empty($options) || empty($providerInstance->startProvide($options))) {
            return false;
        }

        $key = $providerInstance->getClassNameWithoutNamespace();
        return $this->providers[$key] = $providerInstance;
    }

    //Get a provider indicated on key.
    public function getProvider($key)
    {
        return !empty($this->providers[$key]) ? $this->providers[$key] : false;
    }
}
