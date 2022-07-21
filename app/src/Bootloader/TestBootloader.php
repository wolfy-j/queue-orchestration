<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Bootloader;

use App\Service\SomeInterface;
use App\Service\SomeService;
use Spiral\Boot\Bootloader\Bootloader;

class TestBootloader extends Bootloader
{
    protected const SINGLETONS = [
        SomeInterface::class => SomeService::class
    ];
}
