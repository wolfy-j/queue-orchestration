<?php


namespace App\Job;


use Psr\SimpleCache\InvalidArgumentException;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\HandlerInterface;
use Spiral\Queue\Options;
use App\Service\PushService;
use Spiral\Queue\QueueInterface;

class Processor implements HandlerInterface
{
    use PrototypeTrait;
    /** @var PushService */
    private $pushService;
    /** @var QueueInterface */
    private $queue;
    /**
     * @param PushService $pushService
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue, PushService $pushService)
    {
        $this->queue = $queue;
        $this->pushService = $pushService;
    }

    public function handle(string $name, string $id, array $payload): void
    {
        $s = microtime(true);
        if (!isset($payload['redirect'])) {
            $route = $this->pushService->getRoute($payload['group']);
            if ($route !== 'default') {
                // redirect
                $payload['redirect'] = true;
                $this->queue->push(Processor::class, $payload, Options::onQueue($route));
                //dumprr('reroute ' . number_format(microtime(true) - $s, 5));
                return;
            }
        }

        // sleep in ms
        $sleep = mt_rand(100, 3000);

        // DO SOME WORK
        usleep($sleep * 1000);

//        dumprr(
//            'route ' . $payload['route'] . ' ' . $payload['data'] . 'with ' . $sleep . ' ms sleep'
//            . number_format(microtime(true) - $s, 5)
//        );

        $this->pushService->update($payload['group'], -1);
    }
}
