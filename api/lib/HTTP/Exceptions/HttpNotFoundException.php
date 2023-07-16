<?php

namespace Sicet7\HTTP\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Sicet7\Base\HTTP\Enums\Status\ClientError;

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