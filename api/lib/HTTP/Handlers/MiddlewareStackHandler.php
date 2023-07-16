<?php

namespace Sicet7\HTTP\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\Base\HTTP\Interfaces\AcceptsMiddlewareInterface;

class MiddlewareStackHandler implements RequestHandlerInterface, AcceptsMiddlewareInterface
{
    /**
     * @param RequestHandlerInterface $handler
     */
    public function __construct(
        private RequestHandlerInterface $handler
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->handler = new MiddlewareHandler($this->handler, $middleware);
    }
}