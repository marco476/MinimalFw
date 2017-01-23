<?php
require_once __DIR__ . '/src/Kernel/Kernel.php';
require_once __DIR__ . '/src/Providers/Cache/FilesystemCache.php';
use Kernel\Kernel;
use Providers\Cache\FilesystemCache;

FilesystemCache::setGlobal(['cacheDirFromRoot' => $_SERVER["DOCUMENT_ROOT"] . '/cache']);

$kernel = new Kernel(false);

$kernel->setGlobal(['controllerDirFromRoot' => __DIR__ . '/src/Controller']);
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
    http_response_code(404);
    //require_once 'page404.html';
}