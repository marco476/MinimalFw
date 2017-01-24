<?php
require_once __DIR__ . '/vendor/autoload.php';

use Kernel\Kernel;
use Providers\Cache\FilesystemCache;

FilesystemCache::setGlobal(['cacheDirFromRoot' => $_SERVER["DOCUMENT_ROOT"] . '/cache']);

$kernel = new Kernel();

$kernel->setGlobal([
        'controllerDirFromRoot' => __DIR__ . '/src/Controller',
        'viewsDirFromRoot' => __DIR__ . '/src/Views'
        ]);
        
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
}
