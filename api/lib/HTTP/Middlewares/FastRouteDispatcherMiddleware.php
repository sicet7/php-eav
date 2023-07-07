<?php

namespace Sicet7\HTTP\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use FastRoute\Dispatcher as DispatcherInterface;
use Sicet7\HTTP\RequestAttributes\RoutingResult;

class FastRouteDispatcherMiddleware implements MiddlewareInterface
{
    /**
     * @var DispatcherInterface
     */
    private DispatcherInterface $dispatcher;

    /**
     * @param DispatcherInterface $dispatcher
     */
    public function __construct(DispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $resultAttribute = RoutingResult::performRouting($this->dispatcher, $request);
        return $handler->handle($resultAttribute->withAttribute($request));
    }
}