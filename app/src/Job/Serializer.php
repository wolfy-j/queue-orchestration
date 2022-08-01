<?php


namespace App\Job;


use Spiral\Queue\SerializerInterface;

class Serializer implements SerializerInterface
{
    public function serialize(array $payload): string
    {
        return json_encode($payload);
    }

    public function deserialize(string $payload): array
    {
        return json_decode($payload, true);
    }
}
