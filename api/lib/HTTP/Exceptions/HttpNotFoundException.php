<?php

namespace Sicet7\HTTP\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Sicet7\HTTP\Enums\Status\ClientError;
use Sicet7\HTTP\Exceptions\HttpException;

final class HttpNotFoundException extends HttpException
{
    /**
     * @param ServerRequestInterface $request
     * @param \Throwable|null $previous
     */
    public function __construct(
        ServerRequestInterface $request,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $request,
            ClientError::NOT_FOUND,
            ClientError::NOT_FOUND->getReason(),
            $previous
        );
    }
}