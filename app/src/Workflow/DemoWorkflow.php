<?php


namespace App\Workflow;


use App\Activity\RouteActivity;
use App\Activity\GolangInterface;
use Spiral\TemporalBridge\Attribute\AssignWorker;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Promise;
use Temporal\Workflow;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[AssignWorker("demo")]
#[WorkflowInterface]
class DemoWorkflow
{
    /** @var RouteActivity */
    private $act;

    /** @var GolangInterface */
    private $go;

    private $variable;

    public function __construct()
    {
        $this->act = Workflow::newActivityStub(
            RouteActivity::class,
            ActivityOptions::new()
                ->withTaskQueue("demo")
                ->withStartToCloseTimeout(60)
        );

        $this->go = Workflow::newActivityStub(
            GolangInterface::class,
            ActivityOptions::new()
                ->withTaskQueue("golang")
                ->withStartToCloseTimeout(60)
        );
    }

    public function setVariable($var)
    {
        $this->variable = $var;
    }

    #[WorkflowMethod]
    public function run(
        string $name
    ) {
//        $result = [];
//        for ($i = 0; $i < 50; $i++) {
//            $result[] = $this->act->a($name);
//            $result[] = $this->act->b($name);
//        }
//
//        $values = yield Promise::all($result);
//
//        return join(', ', $values);

        return yield $this->go->test($name);
    }
}
