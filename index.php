<?php
require_once __DIR__ . '/src/Kernel/Core.php';
require_once __DIR__ . '/src/Providers/Cache/FilesystemCache.php';
use Kernel\Core;
use Providers\Cache\FilesystemCache;

FilesystemCache::setGlobal(['cacheDirFromRoot' => $_SERVER["DOCUMENT_ROOT"] . '/cache']);

$core = new Core(false);
$core->setRoutes([
        'homepage' => [
            'route' => '/^\/$/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => []
        ]
]);

if ($core->findRoute()) {
    $core->executeAction();
} else {
    http_response_code(404);
    //require_once 'page404.html';
}