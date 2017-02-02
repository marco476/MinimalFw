<?php
namespace Providers\Cache;

class CacheItemPool implements CacheItemPoolInterface
{
    static private $queueSaved;

    public function getItem($key): CacheItem
    {
        $cacheItem = new CacheItem();
        $cacheItem->setKey($key);

        if ($this->hasItem($key)) {
            include CacheGlobal::getCacheDir() . $key;
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
        return file_exists(CacheGlobal::getCacheDir() . $key);
    }

    public function clear()
    {
        if (empty($files = glob(CacheGlobal::getCacheDir() . '*'))) {
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

        return unlink(CacheGlobal::getCacheDir() . $key);
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
        if (!$this->isItemValidForSave($item)) {
            return false;
        }

        $toWrite = '<?php $item=' . var_export($item->get(), true) . '; ?>';

        return ($fileCache = fopen(CacheGlobal::getCacheDir() . $item->getKey(), 'w')) &&
                fwrite($fileCache, $toWrite) &&
                fclose($fileCache);
    }

    public function saveDeferred(CacheItemInterface $item)
    {
        return self::$queueSaved[] = $item;
    }

    public function getQueueSaved()
    {
        return self::$queueSaved;
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

    protected function isItemValidForSave(CacheItemInterface $item)
    {
        return !$item->isKeyEmpty() && !$item->isValueEmpty();
    }
}
