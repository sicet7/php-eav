<?php

namespace Sicet7\HTTP\Abstracts;

use Psr\Http\Message\ServerRequestInterface;
use Sicet7\HTTP\Interfaces\RequestAttributeInterface;

abstract class RequestAttribute implements RequestAttributeInterface
{
    public const ATTRIBUTE_NAME = 'unknown';

    /**
     * @param ServerRequestInterface $request
     * @return static|null
     */
    public static function readAttribute(ServerRequestInterface $request): ?static
    {
        $attribute = $request->getAttribute(static::ATTRIBUTE_NAME);

        if (empty($attribute) || !($attribute instanceof static)) {
            return null;
        }

        return $attribute;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ServerRequestInterface
     */
    public function withAttribute(ServerRequestInterface $request): ServerRequestInterface
    {
        return $request->withAttribute(static::ATTRIBUTE_NAME, $this);
    }
}