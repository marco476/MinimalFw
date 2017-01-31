<?php
namespace Providers\Cache;

//CacheItemPoolInterface generates CacheItemInterface objects.
interface CacheItemPoolInterface
{
    //Returns a Cache Item representing the specified key.
    //This method must always return a CacheItemInterface object
    public function getItem($key);

    //Returns a traversable set of cache items.
    public function getItems(array $keys = array());

    //Confirms if the cache contains specified cache item.
    public function hasItem($key);

    //Deletes all items in the pool.
    public function clear();

    //Removes the item from the pool.
    public function deleteItem($key);

    //Removes multiple items from the pool.
    public function deleteItems(array $keys);

    //Persists a cache item immediately.
    public function save(CacheItemInterface $item);

    //Sets a cache item to be persisted later.
    public function saveDeferred(CacheItemInterface $item);

    //Persists any deferred cache items.
    public function commit();
}