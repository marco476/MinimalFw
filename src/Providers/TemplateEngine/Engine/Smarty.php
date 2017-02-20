<?php
namespace Providers\TemplateEngine\Engine;

class Smarty implements EngineInterface
{
    protected $smartyData = null;
    protected $smartyIstance = null;
    protected $pathDir = null;

    public function __construct($pathDir, array $options)
    {
        $this->pathDir = $pathDir;
        $this->smartyIstance = new \Smarty();
        $this->smartyIstance->setTemplateDir($pathDir)
                            ->setCompileDir($pathDir . '/smartyCompiled')
                            ->setConfigDir($pathDir . '/smartyConfig');

        $this->setCache(!empty($options['cache']));
        $this->smartyData = new \Smarty_Data();
    }

    //Set cache if relative parameter is enabled.
    protected function setCache($cache)
    {
        if(!$cache) {
            return false;
        }  

        $this->smartyIstance->setCacheDir($pathDir . '/cache/smartyCache');
    }

    public function assign($key, $value)
    {
        $this->smartyData->assign($key, $value);
    }

    public function render($file, array $variables = array())
    {
        $this->multiAssign($variables);

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

    protected function multiAssign(array $variables)
    {
        if (!empty($variables)) {
            foreach ($variables as $key => $value) {
                $this->smartyData->assign($key, $value);
            }
        }
    }
}
