<?php

namespace Sicet7\Base\Server;

use Psr\Http\Message\ResponseInterface;

interface ThrowableToResponseConverterInterface
{
    /**
     * @param \Throwable $throwable
     * @return ResponseInterface
     */
    public function convert(\Throwable $throwable): ResponseInterface;
}