<?php

namespace Sicet7\Server;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Sicet7\Base\HTTP\Enums\Status\ServerError;
use Sicet7\Base\Server\ThrowableToResponseConverterInterface;

final readonly class ThrowableToResponseConverter implements ThrowableToResponseConverterInterface
{
    /**
     * @param ResponseFactoryInterface $responseFactory
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    /**
     * @param \Throwable $throwable
     * @return ResponseInterface
     */
    public function convert(\Throwable $throwable): ResponseInterface
    {
        return $this->responseFactory->createResponse(
            ServerError::INTERNAL_SERVER_ERROR->value,
            ServerError::INTERNAL_SERVER_ERROR->getReason()
        );
    }
}