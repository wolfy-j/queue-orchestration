<?php

namespace App\Interceptors;

use App\Attribute\CacheAttribute;
use App\View\DataView;
use Spiral\Core\CoreInterceptorInterface;
use Spiral\Core\CoreInterface;
use Spiral\Prototype\Traits\PrototypeTrait;

class SampleInterceptor implements CoreInterceptorInterface
{
    use PrototypeTrait;

    public function process(
        string $class,
        string $action,
        array $parameters,
        CoreInterface $core
    ) {
//        $ref = new \ReflectionClass($class);
//        $m = $ref->getMethod($action);
//
//        $attrs = $m->getAttributes();
//        foreach ($attrs as $attr) {
//            if ($attr->getName() == CacheAttribute::class) {
//                return 'cached version';
//            }
//        }

        $result = $core->callAction($class, $action, $parameters);

        return $result;
    }
}
