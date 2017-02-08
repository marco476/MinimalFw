# MinimalFw - PHP performance-oriented framework
MinimalFw is a small **PHP** performance-oriented framework for small projects. It work *without any third-library dependency*.

## Installation

You can install it with Composer:

```
composer require minimalfw/minimalfw
```

## Configuration

Configure your front-controller page (assumed *index.php*) it's extreme simple:

```PHP
<?php
//Into web/index.php
require_once __DIR__ . '/../vendor/autoload.php';
use Kernel\Kernel;

$kernel = new Kernel();

//Set your routes!
$kernel->setRoutes([
        'homepage' => [
            'route' => '/^\/$/',
            'controller' => 'IndexController',
            'action' => 'showHomeAction',
            'params' => []
        ]
]);

$kernel->start();
```
The **setRoutes** Kernel's method accept an array of routes, that can be matched with an URI by regular expression setted.
The format is here:

```PHP
$kernel->setRoutes([
        'ROUTE_1' => [
            'route' => '/REGULAR EXPRESSION 1/',
            'controller' => 'NAME CONTROLLER CALL AFTER MATCHED URI',
            'action' => 'NAME ACTION (in Controller) CALL AFTER MATCHED URI',
            'params' => [] //Array of extra parameters
        ],
        'ROUTE_2' => [
            ...
        ],
]);
```

## Controller and Views

All *controller* MUST be insert in **src/Controller** and use *PSR-4* rules.
All *views* MUST be insert in **src/Views**.

See an example for create a controller:

```PHP
<?php
// src/Controller/IndexController.php

namespace Controller;

class IndexController
{
    public function showHomeAction(array $params): array
    {
        //Views included with FIFO approach
        return [
            'common/html/open-page.html',
            'homepage/html/head.php',
            'homepage/html/body.php',
            'common/html/footer.php',
            'common/html/close-page.html',
        ];
    }
}
```

Every controller MUST return an array with name of views, that kernel will include with FIFO approach.
In the last example, *common/html/open-page.html* will be the first include and *common/html/close-page.html* the last.
For detail, see [PSR-4 documentation](http://www.php-fig.org/psr/psr-4/)

## Filesystem Cache
The filesystem cache is quickly and simply, and is implemented with *PSR-6* directive.
See an example:

```PHP
use \Providers\Cache\CacheItemPool;

$itemPool = new CacheItemPool();
$itemCache = $itemPool->getItem('myArray');

if ($itemCache->isHit()) {
    return $itemCache->get();
} else {
    $value = array(
        'name'      => 'Marco',
        'friends'   => array('Paolo','Luca')
    );

    $itemCache->set($value);
    $itemPool->save($itemCache);

    return $value;
}
```

For detail, see [PSR-6 documentation](http://www.php-fig.org/psr/psr-6/)

## SERVER WEB
For enable the front-controller and redirect all URL on it, you must change your web server's configure.
With Apache, you can use a *mod_rewrite* module for rewrite all URL and redirect in your front-controller:

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^(.*)$ /web/index.php [L]
</IfModule>
```
