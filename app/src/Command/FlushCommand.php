<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use App\Database\ChannelRepository;
use Cycle\ORM\EntityManagerInterface;
use Spiral\Console\Command;

class FlushCommand extends Command
{
    protected const NAME = 'flush';
    protected const DESCRIPTION = 'Flush all channel counters';

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
