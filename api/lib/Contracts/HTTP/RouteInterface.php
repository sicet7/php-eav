<?php

namespace Sicet7\Contracts\HTTP;

use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\HTTP\Enums\Method;

interface RouteInterface extends RequestHandlerInterface, HasIdentifierInterface, AcceptsMiddlewareInterface
{
    /**
     * @return Method[]
     */
    public function getMethods(): array;

    /**
     * @return string
     */
    public function getPattern(): string;
}