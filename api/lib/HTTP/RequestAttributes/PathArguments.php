<?php

namespace Sicet7\HTTP\RequestAttributes;

use Sicet7\HTTP\Abstracts\RequestAttribute;

class PathArguments extends RequestAttribute
{
    public const ATTRIBUTE_NAME = 'path-arguments';

    public function __construct(public readonly array $values)
    {
    }
}