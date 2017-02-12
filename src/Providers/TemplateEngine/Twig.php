<?php
namespace Providers\TemplateEngine;

class Twig implements EngineInterface
{
    protected $variables = array();
    protected $twigEnvironment = null;

    protected $availableOptions = array(
        'debug',
        'charset',
        'cache',
        'auto_reload',
        'strict_variables',
        'autoescape',
        'optimizations'
    );

    public function __construct(string $pathDir, array $options)
    {
        $loader = new \Twig_Loader_Filesystem($pathDir);
        $optionsForTwig = $this->setParameters($options);

        return $this->twigEnvironment = new \Twig_Environment($loader, $optionsForTwig);
    }

    public function setParameters(array $options): array
    {
        $result = array();

        foreach ($options as $key => $option) {
            if (in_array($key, $this->availableOptions)) {
                $result[$key] = $option;
            }
        }

        return $result;
    }

    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

    public function render($file, array $variables = [])
    {
        if (!empty($variables)) {
            foreach ($variables as $key => $value) {
                $this->assign($key, $value);
            }
        }

        echo $this->twigEnvironment->render($file, $this->variables);
    }
}
