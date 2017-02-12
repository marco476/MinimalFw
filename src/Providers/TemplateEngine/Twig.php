<?php
namespace Providers\TemplateEngine;

class Twig implements EngineInterface
{
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

        return new \Twig_Environment($loader, $optionsForTwig);
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
}
