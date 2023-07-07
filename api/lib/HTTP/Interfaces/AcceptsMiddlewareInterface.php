<?php

namespace Sicet7\HTTP\Interfaces;

use Psr\Http\Server\MiddlewareInterface;

interface AcceptsMiddlewareInterface
{
    /**
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware): void;
}