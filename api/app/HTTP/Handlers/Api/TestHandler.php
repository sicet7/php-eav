<?php

namespace App\HTTP\Handlers\Api;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\HTTP\Attributes\Get;
use Sicet7\Plugin\Container\Attributes\Autowire;

#[Autowire]
#[Get('/api/test')]
readonly class TestHandler implements RequestHandlerInterface
{
    /**
     * @param ResponseFactoryInterface $responseFactory
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        private ResponseFactoryInterface $responseFactory,
        private StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->responseFactory->createResponse()
            ->withBody($this->streamFactory->createStream('Hello World!'));
    }
}