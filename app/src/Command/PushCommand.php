<?php

/**
 * {project-name}
 *
 * @author {author-name}
 */
declare(strict_types=1);

namespace App\Command;

use Spiral\Console\Command;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\Options;
use Spiral\Queue\QueueInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use App\Service\PushService;

class PushCommand extends Command
{
    use PrototypeTrait;

    protected const NAME = 'push';
    protected const DESCRIPTION = 'Push payload';
    protected const ARGUMENTS = [
        ['group', InputArgument::REQUIRED, 'Group to push'],
        ['value', InputArgument::REQUIRED, 'Payload to push']
    ];

    protected const OPTIONS = [
        ['size', 's', InputOption::VALUE_OPTIONAL, 'Push size', 1]
    ];
    /** @var PushService */
    private $pushService;
    /**
     * @param PushService $pushService
     * @param string|null $name
     */
    public function __construct(PushService $pushService, ?string $name = null)
    {
        parent::__construct($name);
        $this->pushService = $pushService;
    }

    /**
     * Perform command
     */
    protected function perform(): void
    {
        for ($i = 0; $i < $this->option('size'); $i++) {
            $this->pushService->push(
                $this->argument('group'),
                $this->argument('value')
            );
        }

        $this->writeln("<info>OK</info>");
    }
}
