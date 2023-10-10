<?php

namespace Laravel\Cache\Wrappers;

class CacheableWrapper
{
    private $original;

    public function __construct($original)
    {
        $this->original = $original;
    }

    public function __call($method, $args)
    {
        $this->original->$method($args);
    }
}