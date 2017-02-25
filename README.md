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
use Providers\AdminProviders;
use Providers\TemplateEngine\TemplateEngine;

//Set TemplateEngine provider. See next chapter.
AdminProviders::setProvider(new TemplateEngine(), array(
    'name' => TemplateEngine::TWIG,
    'cache' => false
));

$kernel = new Kernel();
$kernel->start();
```

## Providers

### Template Engine

The **setProvider** AdminProviders's static method set a provider. The only one provider that (for now!) you can use is *TemplateEngine*. You can pass an istance
of *TemplateEngine* as first argument, and an array of options as second argument. This array must include the **name** key, that specific the name engine:

* Twig           - ```PHP TemplateEngine::TWIG ```
* Smarty         - ```PHP TemplateEngine::SMARTY ```
* Base (default) - ```PHP TemplateEngine::BASE ```

In Twig (only, at the moment) you can set another keys in the options array ([you can see this list](http://twig.sensiolabs.org/doc/2.x/api.html#environment-options)):
* ```PHP debug ```
* ```PHP charset ```
* ```PHP strict_variables ```
* ```PHP autoescape ```
* ```PHP optimizations ```

You can also use the key **cache** and set it to true or false, for enable or disable cache in Twig and Smarty.

> Note: for Smarty, the config directory is in *src/Views/smartyConfig*

## Routing
**MinimalFw** implements [marco476/routing-manager](https://github.com/marco476/routing-manager) repository as routing manager.

For use it, you must set your routes in *YML config file* named **routes.yml** into **config** directory (from the document root).

See an example:

```YML
 #Into config/routes.yml
homepage: #Name route
    route:      / #Route matchable with URI
    controller: IndexController #Controller invoked if matched
    action:     showHomeAction #Action invoked if matched
    params:     [extraParams] #Extra params for controller
```

## Controller and Views

All *controller* must be insert in **src/Controller** and use [PSR-4](http://www.php-fig.org/psr/psr-4/) rules.

All *views* must be insert in **src/Views**.

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
**MinimalFw** implements [minimalfw/filesystemCache](https://github.com/marco476/filesystem-cache) repository for filesystem cache.

## Server web
For enable the front-controller and redirect all URL on it, you must change your web server's configure.
With Apache, you can use a *mod_rewrite* module for rewrite all URL and redirect in your front-controller:

```
<IfModule mod_rewrite.c>
    RewriteEngine On

    RewriteRule ^(.*)$ /web/index.php [L]
</IfModule>
```
