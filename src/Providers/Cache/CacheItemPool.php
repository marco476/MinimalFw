<?php
namespace Providers\Cache;

class CacheItemPool extends CacheGlobal implements CacheItemPoolInterface
{
    static protected $queueSaved;

    public function __construct()
    {
        parent::__construct();
    }

    public function getItem($key): CacheItem
    {
        $cacheItem = new CacheItem();
        $cacheItem->setKey($key);

        if ($this->hasItem($key)) {
            include $this->getCacheDir() . $key;
            $cacheItem->set($item);
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
        return file_exists($this->getCacheDir() . $key);
    }

    public function clear()
    {
        $files = glob($this->getCacheDir() . '*');

        if (empty($files)) {
            return false;
        }

        foreach ($files as $file) {
            if (!unlink($file)) {
                return false;
            }
        }

        return true;
    }

    public function deleteItem($key)
    {
        if (!$this->hasItem($key)) {
            return false;
        }

        return unlink($this->getCacheDir() . $key);
    }

    public function deleteItems(array $keys)
    {
        foreach ($keys as $key) {
            if (!$this->deleteItem($key)) {
                return false;
            }
        }

        return true;
    }

    public function save(CacheItemInterface $item)
    {
        $toWrite = '<?php $item=' . var_export($item->get(), true) . '; ?>';

        return ($fileCache = fopen($this->getCacheDir() . $item->getKey(), 'w')) &&
                fwrite($fileCache, $toWrite) &&
                fclose($fileCache);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return self::$queueSaved[] = $item;
    }

    public function commit()
    {
        foreach (self::$queueSaved as $item) {
            if (!$this->save($item)) {
                return false;
            }
        }

        return true;
    }
}
