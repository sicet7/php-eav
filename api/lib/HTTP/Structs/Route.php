<?php

namespace Sicet7\HTTP\Structs;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\Contracts\HTTP\HasIdentifierInterface;
use Sicet7\Contracts\HTTP\RouteInterface;
use Sicet7\HTTP\Enums\Method;
use Sicet7\HTTP\Handlers\MiddlewareHandler;

class Route implements RouteInterface
{
    /**
     * @var string
     */
    public readonly string $handlerId;

    /**
     * @param Method[] $methods
     * @param string $pattern
     * @param RequestHandlerInterface $handler
     */
    public function __construct(
        public readonly array $methods,
        public readonly string $pattern,
        private RequestHandlerInterface $handler
    ) {
        array_map(fn(Method $method) => $method, $this->methods);
        if ($this->handler instanceof HasIdentifierInterface) {
            $this->handlerId = $this->handler->getIdentifier();
        } else {
            $this->handlerId = get_class($this->handler);
        }
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->handler = new MiddlewareHandler($this->handler, $middleware);
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
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->handlerId;
    }

    /**
     * @return Method[]
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }
}