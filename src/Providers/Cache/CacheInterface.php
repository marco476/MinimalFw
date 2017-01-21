<?php
namespace Providers\Cache;

interface CacheInterface
{
    public function set(string $key, $data): bool;
    public function get(string $key): bool;
}
