<?php

namespace Sicet7\HTTP\Interfaces;

use Psr\Http\Server\RequestHandlerInterface;

interface HandlerContainerInterface
{
    /**
     * @param string $id
     * @return RequestHandlerInterface
     * @throws \RuntimeException should be thrown if the route could not be found.
     */
    public function getHandler(string $id): RequestHandlerInterface;
}