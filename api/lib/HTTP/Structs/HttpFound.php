<?php

namespace Sicet7\HTTP\Structs;

final readonly class HttpFound
{
    public function __construct(public string $handlerId, public array $vars)
    {
    }
}