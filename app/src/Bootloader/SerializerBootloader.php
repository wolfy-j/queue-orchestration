<?php

namespace App\Bootloader;

use App\Job\Serializer;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Queue\SerializerInterface;

class SerializerBootloader extends Bootloader
{
    protected const SINGLETONS = [
        SerializerInterface::class => Serializer::class
    ];
}
