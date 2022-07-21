<?php

namespace App\Workflow;

use App\Activity\GolangInterface;
use App\Activity\RouteActivity;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityOptions;
use Temporal\Promise;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;

#[AssignWorker("default")]
#[WorkflowInterface]
class RouteWorkflow
{
    /** @var RouteActivity */
    private $route;

    // we are using local queue driver,
    // so you MUST issue new route workflow per roadrunner run
    private array $routes = [];

    public function __construct()
    {
        $this->route = Workflow::newActivityStub(
            RouteActivity::class,
            ActivityOptions::new()
                ->withTaskQueue('default')
                ->withStartToCloseTimeout(60)
        );
    }

    #[Workflow\WorkflowMethod]
    public function start()
    {
        $count = 0;
        while (true) {
            $count++;
            if ($count > 100) {
                // let's keep it running indefinitely with 100 ticks per instance
                return Workflow::continueAsNew('RouteWorkflow', []);
            }

            // todo: stop signal
            yield Workflow::timer(1);

            // list of routes to be updated
            $routes = yield $this->route->calculateRoutes();

            // do we need to issue new queues?
            $wait = [];
            foreach ($routes as $route) {
                if (!is_null($route) && !in_array($route, $this->routes)) {
                    // create slower priority queue (could be specific to group)
                    $wait[] = $this->route->createQueue($route, 100);
                    $this->routes[] = $route;
                }
            }

            // wait for all new queues to be created
            yield Promise::all($wait);

            // redirect traffic
            $wait = [];
            foreach ($routes as $group => $route) {
                // todo: keep activated routes to avoid duplicates

                if ($route === null) {
                    // no longer need to use custom route
                    $updates[] = $this->route->resetRoute($group);
                } else {
                    // redirecting traffic
                    $updates[] = $this->route->updateRoute($group, $route);
                }
            }

            // wait for all the traffic to be redirected
            yield Promise::all($wait);
        }
    }
}
