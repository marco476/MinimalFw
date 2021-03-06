<?php
namespace Providers\TemplateEngine\Engine;

class Twig implements EngineInterface
{
    protected $variables = array();
    protected $twigEnvironment = null;
    protected $pathDir = null;

    protected $availableOptions = array(
        'debug',
        'charset',
        'strict_variables',
        'autoescape',
        'optimizations'
    );

    public function __construct($pathDir, array $options)
    {
        $this->pathDir = $pathDir;

        $loader = new \Twig_Loader_Filesystem($pathDir);
        $this->twigEnvironment = new \Twig_Environment($loader, $this->setParameters($options));
    }

    public function setParameters(array $options)
    {
        $result = array();

        foreach ($options as $key => $option) {
            if (in_array($key, $this->availableOptions)) {
                $result[$key] = $option;
            }
        }

        //Check cache paramter.
        if(!empty($options['cache'])){
            $result['cache'] = $this->pathDir . '/cache/twigCache';
        }

        return $result;
    }

    public function getTwigEnvironment()
    {
        return $this->twigEnvironment;
    }

    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render($file, array $variables = array())
    {
        $this->multiAssign($variables);

        if (is_array($file)) {
            $this->multiRender($file);
        } else {
            echo $this->twigEnvironment->render($file, $this->variables);
        }
    }

    protected function multiRender(array $files)
    {
        foreach ($files as $file) {
            echo $this->twigEnvironment->render($file, $this->variables);
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
