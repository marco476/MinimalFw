<?php
namespace Providers\TemplateEngine;

class Base implements EngineInterface
{
    protected $variables = array();
    protected $pathDir;

    public function __construct(string $pathDir, array $options)
    {
        $this->pathDir = $pathDir;
        //$this->setParameters($options);
    }

    //Not exist parameters for Base Template engine at the moment.
    public function setParameters(array $options): array
    {
        return array();
    }

    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render($file, array $variables = [])
    {
        $this->insertVariables($variables);
        $this->createDefinesFromVariables();

        if (is_array($file)) {
            $this->multiRender($file);
        } else {
            require_once $this->pathDir . '/' . $file;
        }
    }

    protected function multiRender(array $files)
    {
        foreach ($files as $file) {
            require_once $this->pathDir . '/' . $file;
        }
    }

    protected function createDefinesFromVariables()
    {
        foreach ($this->variables as $key => $value) {
            define($key, $value);
        }
    }

    protected function insertVariables(array $variables)
    {
        if (!empty($variables)) {
            foreach ($variables as $key => $value) {
                $this->assign($key, $value);
            }
        }
    }
}
