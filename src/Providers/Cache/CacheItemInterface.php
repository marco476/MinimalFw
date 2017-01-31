<?php
namespace Providers\Cache;

//CacheItemInterface rappresent an item in cache.
interface CacheItemInterface
{
    //Returns the key for the current cache item.
    public function getKey();

    //Retrieves the value of the item from the cache associated with this object's key.
    //If isHit() returns false, this method MUST return null.
    public function get();

    //Confirms if the cache item lookup resulted in a cache hit.
    public function isHit();
    
    //Sets the value represented by this cache item.
    public function set($value);

    //Sets the expiration time for this cache item.
    public function expiresAt($expiration);

    //Sets the expiration time for this cache item.
    public function expiresAfter($time);
}