<?php

namespace App\Bootloader;

use App\Interceptors\SampleInterceptor;
use Spiral\Bootloader\DomainBootloader;
use Spiral\Core\CoreInterface;

class AppBootloader extends DomainBootloader
{
    protected const INTERCEPTORS = [
        SampleInterceptor::class
    ];

    protected const SINGLETONS = [
        CoreInterface::class => [self::class, 'domainCore']
    ];
}
