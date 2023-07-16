<?php

namespace Sicet7\HTTP\RequestAttributes;

use Sicet7\Base\HTTP\Interfaces\RequestAttributeInterface;
use Sicet7\HTTP\Traits\RequestAttributeTrait;

class PathArguments implements RequestAttributeInterface
{
    use RequestAttributeTrait;

    public function __construct(public readonly array $values)
    {
    }
}