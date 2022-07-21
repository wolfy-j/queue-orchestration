<?php

declare(strict_types=1);

namespace Tests\Feature\Job;

use App\Job\HelloJob;
use Spiral\Testing\Queue\FakeQueue;
use Tests\TestCase;

class PingTest extends TestCase
{
    private FakeQueue $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->connection = $this->fakeQueue()->getConnection();
    }

    public function testJobPushed(): void
    {
        $this->connection->push(HelloJob::class, ['value' => 'hello world']);

        $this->connection->assertPushed(HelloJob::class, fn (array $data) =>
            $data['handler'] instanceof HelloJob && $data['payload']['value'] === 'hello world'
        );
    }
}
