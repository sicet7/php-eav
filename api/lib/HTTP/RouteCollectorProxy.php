<?php

namespace Sicet7\HTTP;

use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\HTTP\Interfaces\HandlerContainerInterface;
use Sicet7\HTTP\Interfaces\RouteCollectorInterface;
use Sicet7\HTTP\Interfaces\RouteInterface;
use FastRoute\RouteCollector as FastRouteRouteCollector;
use Sicet7\HTTP\Enums\Method;

class RouteCollectorProxy implements HandlerContainerInterface, RouteCollectorInterface
{
    /**
     * @var RouteInterface[]
     */
    private array $routes = [];

    /**
     * @param string $id
     * @return RequestHandlerInterface
     */
    public function getHandler(string $id): RequestHandlerInterface
    {
        return $this->routes[$id];
    }

    /**
     * @param RouteInterface $route
     * @return void
     */
    public function add(RouteInterface $route): void
    {
        $this->routes[$route->getIdentifier()] = $route;
    }

    /**
     * @param FastRouteRouteCollector $collector
     * @return void
     */
    public function apply(FastRouteRouteCollector $collector): void
    {
        foreach ($this->routes as $route) {
            $collector->addRoute(
                array_map(fn(Method $method) => $method->value, $route->getMethods()),
                $route->getPattern(),
                $route->getIdentifier()
            );
        }
    }
}