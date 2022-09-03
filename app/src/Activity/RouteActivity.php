<?php

namespace App\Activity;

use App\Service\PushService;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[AssignWorker('default')]
#[ActivityInterface(prefix: "route.")]
class RouteActivity
{
    public function __construct(
        private Jobs $jobs,
        private PushService $pushService,
        private ORMInterface $orm
    ) {
    }

    /** Returns list of routes to be updated. */
    #[ActivityMethod()]
    public function calculateRoutes(): array
    {
        $channels = [];
        foreach ($this->pushService->getChannels() as $ch) {
            if ($ch->count > 100 && $ch->route === null) {
                // start using custom queue (priority, max concurrency)
                $channels[$ch->id] = $ch->id;
            }

            if ($ch->count < 10 && $ch->route !== null) {
                // no longer need custom channel for new messages, work in default priority
                $channels[$ch->id] = null;
            }
        }

        return $channels;
    }

    #[ActivityMethod]
    public function createQueue(
        string $route,
        int $priority
    ): void {
        dumprr(sprintf("New queue route `%s`", $route));

        try {
            $this->jobs->create(new MemoryCreateInfo($route, $priority));
            $this->jobs->resume($route);
        } catch (\Throwable $e) {
            dumprr(sprintf("Duplicate queue route `%s`", $route));
        }
    }

    #[ActivityMethod]
    public function updateRoute(
        string $group,
        string $route
    ): void {
        dumprr(sprintf("Routing `%s` to %s", $group, $route));

        $chan = $this->pushService->getChannel($group);
        $chan->route = $route;

        $em = new EntityManager($this->orm);
        $em->persist($chan);
        $em->run();
    }

    #[ActivityMethod]
    public function resetRoute(
        string $group
    ): void {
        dumprr(sprintf("Resetting routing for `%s`", $group));

        $chan = $this->pushService->getChannel($group);
        $chan->route = null;

        $em = new EntityManager($this->orm);
        $em->persist($chan);
        $em->run();
    }
}
