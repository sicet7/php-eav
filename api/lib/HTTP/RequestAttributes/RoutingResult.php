<?php

namespace Sicet7\HTTP\RequestAttributes;

use FastRoute\Dispatcher as DispatcherInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sicet7\Base\HTTP\Interfaces\RequestAttributeInterface;
use Sicet7\HTTP\Exceptions\HttpMethodNotAllowedException;
use Sicet7\HTTP\Exceptions\HttpNotFoundException;
use Sicet7\HTTP\Structs\HttpFound;
use Sicet7\HTTP\Traits\RequestAttributeTrait;

class RoutingResult implements RequestAttributeInterface
{
    use RequestAttributeTrait;

    /**
     * @param DispatcherInterface $dispatcher
     * @param ServerRequestInterface $request
     * @return static
     */
    public static function performRouting(
        DispatcherInterface $dispatcher,
        ServerRequestInterface $request
    ): static {
        $dispatchResults = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );
        return match ($dispatchResults[0]) {
            DispatcherInterface::NOT_FOUND => new static(
                new HttpNotFoundException($request)
            ),
            DispatcherInterface::METHOD_NOT_ALLOWED => new static(
                new HttpMethodNotAllowedException($request, $dispatchResults[1])
            ),
            DispatcherInterface::FOUND => new static(
                new HttpFound($dispatchResults[1], $dispatchResults[2] ?? [])
            ),
            default => throw new \RuntimeException('Routing failed with unknown error.')
        };
    }

    private function __construct(
        public readonly HttpMethodNotAllowedException|HttpNotFoundException|HttpFound $result
    ) {
    }
}