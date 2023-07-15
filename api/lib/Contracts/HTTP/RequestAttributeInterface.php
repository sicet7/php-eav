<?php

namespace Sicet7\Contracts\HTTP;

use Psr\Http\Message\ServerRequestInterface;

interface RequestAttributeInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return static|null
     */
    public static function find(ServerRequestInterface $request): ?static;

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function withAttribute(ServerRequestInterface $request): ServerRequestInterface;

    /**
     * @return string
     */
    public static function getAttributeName(): string;
}