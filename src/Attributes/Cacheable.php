<?php
declare(strict_types=1);

namespace Laravel\Cache\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Cacheable
{
    public $name;
    public $ttl_seconds;
    public $cache_if_null;
    public $cache_if_empty;

    public function __construct(string $name, int $ttl_seconds = 60, bool $cache_if_null = false, bool $cache_if_empty = false)
    {
        $this->name = $name;
        $this->ttl_seconds = $ttl_seconds;
        $this->cache_if_null = $cache_if_null;
        $this->cache_if_empty = $cache_if_empty;
    }
}