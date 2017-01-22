<?php
namespace Providers\Cache;

interface CacheInterface
{
    public function set(string $key, $data, $format = null, $nameDefine = null): bool;
    public function get(string $key): bool;
}
