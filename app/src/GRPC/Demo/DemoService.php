<?php


namespace App\GRPC\Demo;


use Spiral\GRPC;

class DemoService implements DemoServiceInterface
{
    public function GetFeature(GRPC\ContextInterface $ctx, Point $in): Feature
    {
        // TODO: Implement GetFeature() method.
    }
}
