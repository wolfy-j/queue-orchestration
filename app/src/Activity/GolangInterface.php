<?php

namespace App\Activity;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface(prefix: "go.")]
interface GolangInterface
{
    #[ActivityMethod("test")]
    public function test(
        string $name
    ): string;
}
