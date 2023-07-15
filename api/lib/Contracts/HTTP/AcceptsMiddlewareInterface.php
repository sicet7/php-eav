<?php

namespace Sicet7\Contracts\HTTP;

use Psr\Http\Server\MiddlewareInterface;

interface AcceptsMiddlewareInterface
{
    /**
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware): void;
}