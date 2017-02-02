<?php
namespace Providers\Cache;

class CacheItem implements CacheItemInterface
{
    protected $key = null;
    protected $value = null;

    public function getKey()
    {
        return $this->key;
    }

    public function get()
    {
        return $this->value;
    }

    public function isHit()
    {
        return !$this->isKeyEmpty() && file_exists(CacheGlobal::getCacheDir() . $this->key);
    }

    public function setKey($key)
    {
        $this->key = $key;
    }

    public function set($value)
    {
        $this->value = $value;
    }

    public function expiresAt($expiration)
    {
    }

    public function expiresAfter($time)
    {
    }

    public function isKeyEmpty()
    {
        return empty($this->key);
    }

    public function isValueEmpty()
    {
        return empty($this->value);
    }
}
