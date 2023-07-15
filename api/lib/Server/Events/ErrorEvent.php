<?php

namespace Sicet7\Server\Events;

abstract readonly class ErrorEvent
{
    public function __construct(public \Throwable $throwable)
    {
    }
}