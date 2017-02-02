<?php
namespace Providers\Cache;

abstract class CacheGlobal
{
    protected $cacheDir;

    public function __construct()
    {
        $this->cacheDir = $_SERVER["DOCUMENT_ROOT"] . '/../cache/';
    }

    public function getCacheDir()
    {
        return $this->cacheDir;
    }
}
