<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use App\Workflow\RouteWorkflow;
use Spiral\Console\Command;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;
use Temporal\Common\IdReusePolicy;

class StartCommand extends Command
{
    protected const NAME = 'start';
    protected const DESCRIPTION = 'Start queue orchestrator';

    protected function perform(WorkflowClientInterface $workflowClient): void
    {
        $this->write("Queue supervisor: ");
        $wf = $workflowClient->newWorkflowStub(
            RouteWorkflow::class,
            WorkflowOptions::new()
                ->withWorkflowId(gethostname() . '-queue-supervisor')
                ->withWorkflowIdReusePolicy(IdReusePolicy::POLICY_REJECT_DUPLICATE)
        );

        try {
            $workflowClient->start($wf);
            $this->writeln("<info>OK</info>");
        } catch (\Throwable $e) {
            $this->writeln("<error>" . $e->getMessage() . "</error>");
        }
    }
}
