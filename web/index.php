<?php
//web/index.php

require_once __DIR__ . '/../vendor/autoload.php';
use Kernel\Kernel;

$kernel = new Kernel();

//Setta le tue rotte!
$kernel->setRoutes([
        'homepage' => [
            'route' => '/^\/$/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => []
        ]
]);

if ($kernel->findRoute()) {
    $kernel->executeAction();
} else {
    //Gestisci il 404!
}