<?php

namespace Sicet7\Base\HTTP\Interfaces;

use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\Base\HTTP\Enums\Method;

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