<?php
namespace Providers\TemplateEngine;

class Smarty implements EngineInterface
{
    protected $smartyData = null;
    protected $smartyIstance = null;

    protected $availableOptions = array(
        'config_dir',
        'caching'
    );

    public function __construct(string $pathDir, array $options)
    {
        $this->smartyIstance = new \Smarty();
        $this->smartyIstance->setTemplateDir($pathDir)
                            ->setCompileDir($pathDir . '/templace_compiled')
                            ->setCacheDir($pathDir . '/template_cache')
                            ->setConfigDir($pathDir . '/config');

        $this->smartyData = new \Smarty_Data();
        $this->setParameters($options);
    }

    public function setParameters(array $options)
    {
        foreach ($options as $key => $option) {
            if (in_array($key, $this->availableOptions)) {
                $camelCaseKey = 'set' . $this->getCamelCase($key);
                $this->smartyIstance->{$camelCaseKey}($option);
            }
        }
    }

    public function assign($key, $value)
    {
        $this->smartyData->assign($key, $value);
    }

    public function render($file, array $variables = array())
    {
        $this->insertVariables($variables);

        if (is_array($file)) {
            $this->multiRender($file);
        } else {
            $this->smartyIstance->display($file, $this->smartyData);
        }
    }

    protected function multiRender(array $files)
    {
        foreach ($files as $file) {
            $this->smartyIstance->display($file, $this->smartyData);
        }
    }

    protected function insertVariables(array $variables)
    {
        if (!empty($variables)) {
            foreach ($variables as $key => $value) {
                $this->smartyData->assign($key, $value);
            }
        }
    }

    //Get a camel case version of $key.
    protected function getCamelCase($key)
    {
        return preg_replace_callback('/(^(\w){1})(.*)_(\w{1})(.*+)/', function ($match) {
            return strtoupper($match[1]) . $match[3] . strtoupper($match[4]) . $match[5];
        }, $key);
    }
}
