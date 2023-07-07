<?php

namespace Sicet7\HTTP\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class MiddlewareHandler implements RequestHandlerInterface
{
    /**
     * @param RequestHandlerInterface $handler
     * @param MiddlewareInterface $middleware
     */
    public function __construct(
        private RequestHandlerInterface $handler,
        private MiddlewareInterface $middleware,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middleware->process($request, $this->handler);
    }
}