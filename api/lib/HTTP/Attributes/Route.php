<?php

namespace Sicet7\HTTP\Attributes;

use Psr\Container\ContainerInterface;
use Sicet7\Base\HTTP\Enums\Method;
use Sicet7\HTTP\Handlers\DeferredHandler;
use Sicet7\HTTP\Structs\Route as RouteStruct;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class Route
{
    /**
     * @var Method[]
     */
    public readonly array $methods;

    /**
     * @param string $pattern
     * @param Method ...$methods
     */
    public function __construct(
        public readonly string $pattern,
        Method ... $methods
    ) {
        $this->methods = $methods;
    }

    /**
     * @param ContainerInterface $container
     * @param string $class
     * @return RouteStruct
     */
    public function makeRoute(ContainerInterface $container, string $class): RouteStruct
    {
        return new RouteStruct(
            $this->methods,
            $this->pattern,
            new DeferredHandler($container, $class)
        );
    }
}