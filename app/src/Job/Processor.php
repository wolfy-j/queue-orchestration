<?php

namespace App\Job;

use Spiral\Queue\HandlerInterface;
use Spiral\Queue\Options;
use App\Service\PushService;
use Spiral\Queue\QueueInterface;

class Processor implements HandlerInterface
{
    public function __construct(
        private QueueInterface $queue,
        private PushService $pushService
    ) {
    }

    public function handle(string $name, string $id, array $payload): void
    {
        if (!isset($payload['redirect'])) {
            $route = $this->pushService->getRoute($payload['group']);
            if ($route !== 'default') {
                // redirect
                $payload['redirect'] = true;
                $this->queue->push(
                    Processor::class,
                    $payload,
                    Options::onQueue($route)
                );
                return;
            }
        }

        // sleep in ms
        $sleep = mt_rand(100, 3000);

        // DO SOME WORK
        usleep($sleep * 1000);

        $this->pushService->update($payload['group'], -1);
    }
}
