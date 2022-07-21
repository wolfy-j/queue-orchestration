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
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Symfony\Component\Console\Input\InputArgument;
use Temporal\Client\WorkflowClientInterface;

class StartCommand extends Command
{
    protected const NAME = 'start';
    protected const DESCRIPTION = '';
    protected const ARGUMENTS = [];
    protected const OPTIONS = [];

    protected function perform(Jobs $jobs, WorkflowClientInterface $workflowClient): void
    {
        try {
            $this->write("Default queue: ");
            $jobs->create(new MemoryCreateInfo('default', 1));
            $jobs->resume('default');
            $this->writeln("<info>OK</info>");
        } catch (\Throwable) {
            $this->writeln("<error>FAIL</error>");
        }

        $this->write("Queue supervisor: ");
        $wf = $workflowClient->newWorkflowStub(RouteWorkflow::class);
        $workflowClient->start($wf);
        $this->writeln("<info>OK</info>");
    }
}
