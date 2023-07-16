<?php

namespace Sicet7\HTTP\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Sicet7\Base\HTTP\Enums\Status\ClientError;

class HttpMethodNotAllowedException extends HttpException
{
    /**
     * @param ServerRequestInterface $request
     * @param array $allowedMethods
     * @param \Throwable|null $previous
     */
    public function __construct(
        ServerRequestInterface $request,
        public readonly array $allowedMethods = [],
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $request,
            ClientError::METHOD_NOT_ALLOWED,
            ClientError::METHOD_NOT_ALLOWED->getReason(),
            $previous
        );
    }
}