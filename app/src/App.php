<?php

/**
 * This file is part of Spiral package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App;

use App\Bootloader;
use Spiral\Bootloader as Framework;
use Spiral\DotEnv\Bootloader as DotEnv;
use Spiral\Framework\Kernel;
use Spiral\Monolog\Bootloader as Monolog;
use Spiral\Scaffolder\Bootloader as Scaffolder;
use Spiral\Cycle\Bootloader as CycleBridge;
use Spiral\RoadRunnerBridge\Bootloader as RoadRunnerBridge;
use Spiral\TemporalBridge\Bootloader\TemporalBridgeBootloader;

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        // Base extensions
        DotEnv\DotenvBootloader::class,
        Monolog\MonologBootloader::class,

        // RoadRunner
        RoadRunnerBridge\CacheBootloader::class,
        RoadRunnerBridge\QueueBootloader::class,
        RoadRunnerBridge\RoadRunnerBootloader::class,

        // Application specific logs
        Bootloader\LoggingBootloader::class,

        // Core Services
        Framework\SnapshotsBootloader::class,

        // Databases
        CycleBridge\DatabaseBootloader::class,
        CycleBridge\MigrationsBootloader::class,
        CycleBridge\DisconnectsBootloader::class,

        // ORM
        CycleBridge\SchemaBootloader::class,
        CycleBridge\CycleOrmBootloader::class,
        CycleBridge\AnnotatedBootloader::class,
        CycleBridge\CommandBootloader::class,

        // Framework commands
        Framework\CommandBootloader::class,
        Scaffolder\ScaffolderBootloader::class,

        // Debug and debug extensions
        Framework\DebugBootloader::class,
        Framework\Debug\LogCollectorBootloader::class,
        Framework\Debug\HttpCollectorBootloader::class,

        RoadRunnerBridge\CommandBootloader::class,

        // Temporal functionality
        TemporalBridgeBootloader::class,
    ];

    /*
     * Application specific services and extensions.
     */
    protected const APP = [
        // application specific serializer
        Bootloader\SerializerBootloader::class
    ];
}
