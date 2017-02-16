<?php
namespace Providers\TemplateEngine;

use Helper\ErrorHelper;
use Providers\ProvidersInterface;

class TemplateEngine implements ProvidersInterface
{
    const TWIG = 'Twig';
    const SMARTY = 'Smarty';
    const BASE = 'Base';
    
    protected $engine = null;
    protected $pathDir;
    protected $listTemplateEngine = array();

    public function __construct()
    {
        $this->listTemplateEngine = array(self::TWIG, self::SMARTY, self::BASE);
        $this->pathDir = $_SERVER["DOCUMENT_ROOT"] . '/../src/Views';
    }

    public function startProvide(array $options)
    {
        if (empty($options) || !$this->checkRequiredParameters($options)) {
            return false;
        }

        if (!is_dir($this->pathDir)) {
            $this->createDir();
        }

        $nameEngine = in_array($options['name'], $this->listTemplateEngine) ? ('\\Providers\\TemplateEngine\\' . $options['name']) : 'Base';
        $instanceEngine = new $nameEngine($this->pathDir, $options);

        return $this->engine = $instanceEngine;
    }

    //Get path's views.
    public function getPathDir()
    {
        return $this->pathDir;
    }

    //Get a list of template engine instantiable.
    public function getListTemplateEngine()
    {
        return $this->listTemplateEngine;
    }

    //Get the instance of template engine setted by set method.
    public function getEngine()
    {
        return $this->engine;
    }

    public function getClassNameWithoutNamespace()
    {
        return explode('\\', __CLASS__)[2];
    }

    public function checkRequiredParameters(array $options)
    {
        return !empty($options['name']);
    }

    protected function createDir()
    {
        if (mkdir($this->pathDir) == false) {
            ErrorHelper::setError(ErrorHelper::DIR_VIEWS_NOT_CREATE, ErrorHelper::FATAL);
        }

        return true;
    }
}
