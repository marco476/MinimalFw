<?php
namespace Providers\TemplateEngine\Engine;

interface EngineInterface
{
    //Transfer a variable (key, value) to template
    public function assign($key, $value);
    
    //Render a template
    public function render($file, array $variables = array());
}
