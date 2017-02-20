<?php
namespace Providers\TemplateEngine\Engine;

class Base implements EngineInterface
{
    protected $variables = array();
    protected $pathDir;

    public function __construct($pathDir, array $options)
    {
        $this->pathDir = $pathDir;
    }

    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render($file, array $variables = array())
    {
        $this->multiAssign($variables);
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

    protected function multiAssign(array $variables)
    {
        if (!empty($variables)) {
            foreach ($variables as $key => $value) {
                $this->assign($key, $value);
            }
        }
    }
}
