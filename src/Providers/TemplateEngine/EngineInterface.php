<?php
namespace Providers\TemplateEngine;

interface EngineInterface
{
    //Set parameters for template engine chosen
    public function setParameters(array $options): array;
}
