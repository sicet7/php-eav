<?php

namespace Sicet7\Events;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Sicet7\Events\Exceptions\InvalidListenerException;
use Sicet7\Events\Interfaces\BoundListenerInterface;
use Sicet7\Events\Interfaces\EventListenerInterface;

readonly class ResolvableListener implements BoundListenerInterface
{
    public function __construct(
        private string $eventFqcn,
        private ContainerInterface $container,
        private string $fqcn
    ) {
    }

    /**
     * @param object $event
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws InvalidListenerException
     */
    public function execute(object $event): void
    {
        //TODO: Implement async events.
        $listener = $this->container->get($this->fqcn);
        match (true) {
            ($listener instanceof EventListenerInterface) => $listener->execute($event),
            method_exists($listener, '__invoke') || is_callable($listener) => $listener($event),
            default => throw new InvalidListenerException('Failed to execute listener: "' . $this->fqcn . '"'),
        };
    }

    /**
     * @param object $event
     * @return bool
     */
    public function listensFor(object $event): bool
    {
        return ltrim(get_class($event), '\\') === ltrim($this->eventFqcn, '\\');
    }
}