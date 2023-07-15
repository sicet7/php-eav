<?php

namespace Sicet7\Events;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Sicet7\Events\Exceptions\ListenerExecutionException;
use Sicet7\Events\Interfaces\EventListenerInterface;

class EventDispatcher implements EventDispatcherInterface
{
    /**
     * @param ListenerProviderInterface $listenerProvider
     */
    public function __construct(
        private readonly ListenerProviderInterface $listenerProvider
    ) {
    }

    /**
     * @param object $event
     * @return void
     */
    public function dispatch(object $event)
    {
        $listeners = $this->listenerProvider->getListenersForEvent($event);
        foreach ($listeners as $listener) {
            if (
                $event instanceof StoppableEventInterface &&
                $event->isPropagationStopped()
            ) {
                return;
            }
            if ($listener instanceof EventListenerInterface) {
                $listener->execute($event);
            } elseif (is_callable($listener)) {
                $listener($event);
            }
        }
    }
}