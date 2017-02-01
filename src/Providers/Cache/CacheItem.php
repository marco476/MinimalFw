<?php
namespace Providers\Cache;

class CacheItem implements CacheItemInterface
{
    protected $key = null;
    protected $value = null;

    public function setData($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

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
