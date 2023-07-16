<?php

namespace Sicet7\Base\HTTP\Interfaces;

use FastRoute\RouteCollector as FastRouteRouteCollector;

interface RouteCollectorInterface
{
    /**
     * @param RouteInterface $route
     * @return void
     */
    public function add(RouteInterface $route): void;

    /**
     * @param FastRouteRouteCollector $collector
     * @return void
     */
    public function apply(FastRouteRouteCollector $collector): void;
}