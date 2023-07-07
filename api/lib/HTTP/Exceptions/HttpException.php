<?php

namespace Sicet7\HTTP\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Sicet7\HTTP\Enums\Status\ClientError;
use Sicet7\HTTP\Enums\Status\ServerError;

abstract class HttpException extends \RuntimeException
{
    /**
     * @param ServerRequestInterface $request
     * @param ClientError|ServerError $code
     * @param string|null $message
     * @param \Throwable|null $previous
     */
    public function __construct(
        public readonly ServerRequestInterface $request,
        ClientError|ServerError $code,
        ?string $message = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $message ?? $code->getReason(),
            $code->value,
            $previous
        );
    }
}