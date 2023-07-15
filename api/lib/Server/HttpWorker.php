<?php

namespace Sicet7\Server;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sicet7\Server\Events\InternalServerError;
use Sicet7\Server\Events\PostDispatch;
use Sicet7\Server\Events\PreDispatch;
use Sicet7\Server\Events\RoadRunnerCommunicationsError;
use Sicet7\Server\Events\TerminateWorker;
use Sicet7\Server\Events\ApplicationError;
use Spiral\RoadRunner\Http\PSR7WorkerInterface;

final readonly class HttpWorker
{
    public function __construct(
        private RequestHandlerInterface $requestHandler,
        private PSR7WorkerInterface $PSR7Worker,
        private ResponseFactoryInterface $responseFactory,
        private ?LoggerInterface $logger = null,
        private ?EventDispatcherInterface $eventDispatcher = null,
    ) {
    }

    /**
     * @return never
     */
    public function run(): never
    {
        exit($this->processLoop());
    }

    /**
     * @return int
     */
    public function processLoop(): int
    {
        do {
            try {
                $request = $this->PSR7Worker->waitRequest();

                if (!($request instanceof ServerRequestInterface)) {
                    $this->log(
                        LogLevel::INFO,
                        'Termination request received'
                    );
                    if (!$this->dispatch(new TerminateWorker())) {
                        $this->internalServerErrorResponse();
                        return 1;
                    }
                    return 0;
                }

                $this->PSR7Worker->respond($this->handleRequest($request));
            } catch (\JsonException $jsonException) {
                $this->dispatch(new RoadRunnerCommunicationsError($jsonException));
                $this->internalServerErrorResponse($jsonException);
                return 1;
            } catch (\Throwable $throwable) {
                var_dump($throwable);
                die;
                $this->dispatch(new InternalServerError($throwable));
                $this->internalServerErrorResponse($throwable);
                return 1;
            }
        } while(true);
    }

    /**
     * @param \Throwable|null $throwable
     * @return ResponseInterface
     */
    protected function createInternalServerErrorResponse(?\Throwable $throwable = null): ResponseInterface
    {
        return $this->responseFactory->createResponse(500, 'Internal Server Error');
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    private function handleRequest(ServerRequestInterface $request): ResponseInterface
    {
        try {
            $this->eventDispatcher?->dispatch(new PreDispatch($request));
            $response = $this->requestHandler->handle($request);
            $this->eventDispatcher?->dispatch(new PostDispatch($response));
            return $response;
        } catch (\Throwable $throwable) {
            $this->dispatch(new ApplicationError($throwable));
            return $this->createInternalServerErrorResponse($throwable);
        }
    }

    /**
     * @param string $level
     * @param string $msg
     * @param \Throwable|null $throwable
     * @return void
     */
    private function log(string $level, string $msg, ?\Throwable $throwable = null): void
    {
        if ($this->logger === null) {
            return;
        }
        $context = [];
        if ($throwable !== null) {
            $context['throwable'] = $this->throwableToArray($throwable);
        }
        $this->logger->log($level, $msg, $context);
    }

    /**
     * @param object $event
     * @return bool
     */
    private function dispatch(object $event): bool
    {
        try {
            $this->eventDispatcher?->dispatch($event);
            return true;
        } catch (\Throwable $throwable) {
            $this->log(
                LogLevel::ERROR,
                'Failed to dispatch server event',
                $throwable
            );
            return false;
        }
    }

    /**
     * @param \Throwable|null $throwable
     * @return void
     */
    private function internalServerErrorResponse(?\Throwable $throwable = null): void
    {
        try {
            $this->PSR7Worker->respond(
                $this->createInternalServerErrorResponse($throwable)
            );
        } catch (\Throwable $throwable) {
            $this->log(
                LogLevel::CRITICAL,
                'Failed to deliver internal server error response',
                $throwable
            );
        }
    }

    /**
     * @param \Throwable $throwable
     * @param int $levels
     * @return array
     */
    private function throwableToArray(\Throwable $throwable, int $levels = 3): array
    {
        $output = [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => $throwable->getTrace(),
        ];
        if ($throwable->getPrevious() instanceof \Throwable && $levels > 0) {
            $output['previous'] = $this->throwableToArray($throwable->getPrevious(), $levels - 1);
        }
        return $output;
    }
}