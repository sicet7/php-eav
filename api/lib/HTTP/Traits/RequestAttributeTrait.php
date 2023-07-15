<?php

namespace Sicet7\HTTP\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait RequestAttributeTrait
{
    /**
     * @param ServerRequestInterface $request
     * @return static|null
     */
    public static function find(ServerRequestInterface $request): ?static
    {
        $attribute = $request->getAttribute(static::getAttributeName());

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
        return $request->withAttribute(static::getAttributeName(), $this);
    }

    /**
     * @return string
     */
    public static function getAttributeName(): string
    {
        return static::class;
    }
}