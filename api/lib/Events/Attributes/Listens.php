<?php

namespace Sicet7\Events\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
readonly class Listens
{
    public function __construct(public string $eventFqcn)
    {
    }
}