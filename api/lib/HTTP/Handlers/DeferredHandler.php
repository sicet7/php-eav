<?php

namespace Sicet7\HTTP\Handlers;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Sicet7\Contracts\HTTP\HasIdentifierInterface;

class DeferredHandler implements RequestHandlerInterface, HasIdentifierInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $class
     */
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly string $class,
    ) {
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->container->get($this->class)->handle($request);
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->class;
    }
}