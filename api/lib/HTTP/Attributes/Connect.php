<?php

namespace Sicet7\HTTP\Attributes;

use Sicet7\Base\HTTP\Enums\Method;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Connect extends Route
{
    /**
     * @param string $pattern
     */
    public function __construct(string $pattern)
    {
        parent::__construct($pattern, Method::CONNECT);
    }
}