<?php

namespace Sicet7\HTTP\Handlers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\Contracts\HTTP\HandlerContainerInterface;
use Sicet7\HTTP\Exceptions\HttpException;
use Sicet7\HTTP\RequestAttributes\PathArguments;
use Sicet7\HTTP\RequestAttributes\RoutingResult;

readonly class RouteInvokerHandler implements RequestHandlerInterface
{
    /**
     * @param HandlerContainerInterface $handlerContainer
     */
    public function __construct(
        private HandlerContainerInterface $handlerContainer
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $routingResult = RoutingResult::find($request)?->result;
        if ($routingResult === null) {
            throw new \RuntimeException('Routing was not performed before routing handler.');
        }
        if ($routingResult instanceof HttpException) {
            throw $routingResult;
        }
        $arguments = new PathArguments($routingResult->vars);
        return $this->handlerContainer
            ->getHandler($routingResult->handlerId)
            ->handle($arguments->withAttribute($request));
    }
}