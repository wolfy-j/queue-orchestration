<?php

namespace App\Attribute;

#[\Attribute]
class CacheAttribute
{
    public string $key = '';

    public function __construct(string $key)
    {
        $this->key = $key;
    }
}
