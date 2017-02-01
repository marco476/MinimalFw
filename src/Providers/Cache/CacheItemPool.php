<?php
namespace Providers\Cache;

class CacheItemPool implements CacheItemPoolInterface
{
    protected $cacheDir;

    public function __construct()
    {
        $this->cacheDir = $_SERVER["DOCUMENT_ROOT"] . '/../cache/';
    }

    public function getItem($key): CacheItem
    {
        $cacheItem = new CacheItem();

        if ($this->hasItem($key)) {
            require_once $this->cacheDir . $key;
            $cacheItem->setData($key, $item);
        }

        return $cacheItem;
    }

    public function getItems(array $keys = array())
    {
        $cacheItems = array();

        foreach ($keys as $key) {
            $cacheItems[] = $this->getItem($key);
        }

        return $cacheItems;
    }

    public function hasItem($key)
    {
        if (file_exists($this->cacheDir . $key)) {
            return true;
        }

        return false;
    }

    public function clear()
    {
        $files = glob($this->cacheDir . '*');

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            unlink($file);
        }

        return true;
    }

    public function deleteItem($key)
    {
    }

    public function deleteItems(array $keys)
    {
    }

    public function save(CacheItemInterface $item)
    {
    }

    public function saveDeferred(CacheItemInterface $item)
    {
    }

    public function commit()
    {
    }
}