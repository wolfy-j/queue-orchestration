<?php

namespace App\Service;

use App\Database\Channel;
use App\Job\Processor;
use Cycle\Database\Injection\Expression;
use Cycle\ORM\EntityManager;
use Cycle\ORM\Transaction;
use Spiral\Core\Container\SingletonInterface;
use Spiral\Prototype\Annotation\Prototyped;
use Spiral\Prototype\Traits\PrototypeTrait;
use Spiral\Queue\Options;
use Spiral\Queue\QueueInterface;
use Cycle\Database\DatabaseInterface;
use Cycle\ORM\ORMInterface;
use App\Database\ChannelRepository;

#[Prototyped("pushService")]
class PushService implements SingletonInterface
{
    use PrototypeTrait;

    /** @var QueueInterface */
    private $queue;
    /** @var DatabaseInterface */
    private $db;
    /** @var ORMInterface */
    private $orm;
    /** @var ChannelRepository */
    private $channels;

    /**
     * @param QueueInterface    $queue
     * @param DatabaseInterface $db
     * @param ORMInterface      $orm
     * @param ChannelRepository $channels
     */
    public function __construct(
        ChannelRepository $channels,
        ORMInterface $orm,
        DatabaseInterface $db,
        QueueInterface $queue
    ) {
        $this->channels = $channels;
        $this->orm = $orm;
        $this->db = $db;
        $this->queue = $queue;
    }

    public function push(string $group, string $data)
    {
        $route = $this->getRoute($group);

        $this->queue->push(
            Processor::class,
            [
                'group' => $group,
                'route' => $route,
                'data'  => $data
            ],
            Options::onQueue($route)
        );

        $this->update($group, +1);
    }

    /**
     * Must be concurrent read safe. Hot function.
     *
     * @param string $group
     * @return string
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getRoute(string $group): string
    {
        $rs = $this->db->select('route')
            ->from('channels')
            ->where('id', '=', $group)
            ->fetchAll();

        if ($rs !== [] && $rs[0]['route'] !== null) {
            return $rs[0]['route'];
        }

        return 'default';
    }

    public function getChannel(string $group): ?Channel
    {
        return $this->channels->findByPK($group);
    }

    /**
     * Concurrent safe!
     *
     * @param string $group
     * @param int    $count
     */
    public function update(string $group, int $count = +1)
    {
        $ok = $this->db->update('channels')
            ->set('count', new Expression('count + ?', $count))
            ->where('id', '=', $group)
            ->run();

        if (!$ok) {
            // this is not OK. Must concurrent safe, add exception from EM
            $channel = new Channel($group);
            $channel->count = $count;

            $em = new EntityManager($this->orm);
            $em->persist($channel);
            $em->run();
        }
    }

    /**
     * @return Channel[]
     */
    public function getCounts(): array
    {
        return $this->channels->findAll();
    }
}
