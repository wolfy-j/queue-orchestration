<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Bootloader;

use Spiral\Console\Bootloader\ConsoleBootloader;
use Spiral\Goridge;
use Spiral\RoadRunner;
use Spiral\RoadRunner\Metrics;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\RoadRunner\Metrics\MetricsInterface;

class MetricsBootloader extends Bootloader
{
    protected const SINGLETONS = [
        MetricsInterface::class => [self::class, 'metrics']
    ];

    public function boot(MetricsInterface $metrics): void
    {
        // todo: check if in HTTP?
        $metrics->declare(
            'sample',
            Metrics\Collector::counter()->withHelp('Sample metric')
        );
    }

    public function metrics()
    {
        return new Metrics\Metrics(
            Goridge\RPC\RPC::create(RoadRunner\Environment::fromGlobals()->getRPCAddress())
        );
    }
}
