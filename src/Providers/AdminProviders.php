<?php
namespace Providers;

use Providers\ProvidersInterface;

class AdminProviders
{
    //List of all providers setted.
    static protected $providers = array();

    //Set a provider.
    public static function setProvider(ProvidersInterface $providerInstance, array $options)
    {
        if (empty($options) || empty($providerInstance->startProvide($options))) {
            return false;
        }

        $key = $providerInstance->getClassName();
        return self::$providers[$key] = $providerInstance;
    }

    //Get a provider indicated on key.
    public static function getProvider($key)
    {
        return !empty(self::$providers[$key]) ? self::$providers[$key] : false;
    }
}