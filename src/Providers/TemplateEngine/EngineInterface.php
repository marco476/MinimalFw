<?php
namespace Providers\TemplateEngine;

interface EngineInterface
{
    //Set parameters for template engine
    public function setParameters(array $options): array;
    //Transfer a variable (key, value) to template engine
    public function assign($key, $value);
    //Render a template engine
    public function render($file, array $variables = []);
}
