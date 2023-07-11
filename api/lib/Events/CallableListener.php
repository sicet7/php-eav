<?php

namespace Sicet7\Events;

use Sicet7\Events\Interfaces\BoundListenerInterface;

readonly class CallableListener implements BoundListenerInterface
{
    private \Closure $closure;

    public function __construct(
        private string $eventFqcn,
        callable $callable
    ) {
        $this->closure = $callable(...)->bindTo($this);
    }

    /**
     * @param object $event
     * @return void
     */
    public function execute(object $event): void
    {
        $closure = $this->closure;
        $closure($event);
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