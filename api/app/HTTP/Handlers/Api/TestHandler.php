<?php

namespace App\HTTP\Handlers\Api;

use Doctrine\DBAL\Connection;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Sicet7\HTTP\Attributes\Get;
use Sicet7\Plugin\Attributes\Autowire;

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
        private Connection $connection,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $result = $this->connection->executeQuery('SHOW PROCESSLIST');
            return $this->responseFactory->createResponse()
                ->withBody($this->streamFactory->createStream(var_export($result->fetchAssociative(), true)));
        } catch (\Throwable $throwable) {
            $this->logger->error('Something exploded.', [ 'throwable' => $throwable ]);
            return $this->responseFactory->createResponse(500, 'Internal Server Error');
        }
    }
}