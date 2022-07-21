<?php

namespace App\View;

class DataView
{
    public array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }
}
