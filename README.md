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
//Into web/index.php.
require_once __DIR__ . '/../vendor/autoload.php';
use Kernel\Kernel;

$kernel = new Kernel();

//Set TemplateEngine provider. See next chapter.
$kernel->setProvider(new TemplateEngine(), array(
    'name' => TemplateEngine::TWIG,
    'debug' => true,
    'cache' => false
));

//Set your routes!
$kernel->setRoutes([
        'homepage' => [ //Name route
            'route' => '/^\/$/', //Regular Expression for match URI
            'controller' => 'IndexController', //Controller name invoked if match
            'action' => 'showHomeAction', //Action's controller invoked if match
            'params' => [] //Extra params for action
        ]
]);

$kernel->start();
```
The **setRoutes** Kernel's method accept an array of routes, that can be matched with an URI by regular expression setted.

## Providers

#### Template Engine

The **setProvider** Kernel's method set a provider. The only one provider that (for now!) you can use is *TemplateEngine*. You can pass an istance
of *TemplateEngine* as first argument, and an array of options as second argument. This array must include the **name** key, that specific the name engine:

* Twig => TemplateEngine::TWIG
* Smarty => TemplateEngine::SMARTY
* Base (default, you can not set) => TemplateEngine::BASE

In Twig (only, at the moment) you can set another keys in the options array ([see the complete list](http://twig.sensiolabs.org/doc/2.x/api.html#environment-options)):
* debug
* charset
* strict_variables
* autoescape
* optimizations

You can also use the key **cache** and set it to true or false, for enable or disable cache in Twig or Smarty.

> Note: for Smarty, the config directory is in *src/Views/smartyConfig*

## Controller and Views

All *controller* MUST be insert in **src/Controller** and use [PSR-4](http://www.php-fig.org/psr/psr-4/) rules.

All *views* MUST be insert in **src/Views**.

See an example for create a controller:

```PHP
<?php
namespace Controller;
use Providers\TemplateEngine\Engine\EngineInterface;

class IndexController
{
    //$params is setting by route settings. 
    //$template is a template engine.
    public function showHomeAction(array $params, EngineInterface $template)
    {
        $template->assign('friend', array(
            'name'      => 'Marco',
            'gender'    => 'male'
        ));

        $template->render('test.twig'); //Single render
        $template->render(array('primo.twig', 'secondo.twig')); //Multi render
    }
}

```
A controller have two arguments: an array of options, setted by setRoutes for any route, and a instance
of template engine. Whether template engine you use, you can use a **assign** method for assign variables
from PHP to template, and **render** method, for view one (as a string) or multi (as an array, included with FIFO
approach) templates.

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

## Server web
For enable the front-controller and redirect all URL on it, you must change your web server's configure.
With Apache, you can use a *mod_rewrite* module for rewrite all URL and redirect in your front-controller:

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^(.*)$ /web/index.php [L]
</IfModule>
```
