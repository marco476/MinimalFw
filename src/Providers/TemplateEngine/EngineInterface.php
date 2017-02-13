<?php
namespace Providers\TemplateEngine;

interface EngineInterface
{
    //Set parameters for template
    public function setParameters(array $options);
    //Transfer a variable (key, value) to template
    public function assign($key, $value);
    //Render a template
    public function render($file, array $variables = array());
}
