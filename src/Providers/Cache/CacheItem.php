<?php
namespace Providers\Cache;

class CacheItem extends CacheGlobal implements CacheItemInterface
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
        return file_exists($this->getCacheDir() . $this->getKey());
    }

    public function setKey($key){
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
}
