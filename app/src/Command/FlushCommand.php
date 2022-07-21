<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use App\Database\ChannelRepository;
use App\Workflow\RouteWorkflow;
use Cycle\ORM\EntityManagerInterface;
use Spiral\Console\Command;
use Spiral\RoadRunner\Jobs\Jobs;
use Spiral\RoadRunner\Jobs\Queue\MemoryCreateInfo;
use Symfony\Component\Console\Input\InputArgument;
use Temporal\Client\WorkflowClientInterface;

class FlushCommand extends Command
{
    protected const NAME = 'flush';
    protected const DESCRIPTION = '';
    protected const ARGUMENTS = [];
    protected const OPTIONS = [];

    protected function perform(ChannelRepository $repository, EntityManagerInterface $em): void
    {
        foreach ($repository->findAll() as $channel) {
            $channel->count = 0;
            $channel->route = null;
            $em->persist($channel);
        }

        $em->run();

        $this->writeln("<info>OK</info>");
    }
}
