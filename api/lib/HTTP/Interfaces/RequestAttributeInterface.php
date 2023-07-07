<?php

namespace Sicet7\HTTP\Interfaces;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAttributeInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return static|null
     */
    public static function readAttribute(ServerRequestInterface $request): ?static;

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function withAttribute(ServerRequestInterface $request): ServerRequestInterface;
}