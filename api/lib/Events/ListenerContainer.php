<?php

namespace Sicet7\Events;

use Sicet7\Events\Interfaces\BoundListenerInterface;
use Sicet7\Events\Interfaces\ListenerContainerInterface;

class ListenerContainer implements ListenerContainerInterface
{
    /**
     * @var BoundListenerInterface[]
     */
    private array $listeners = [];

    /**
     * @param BoundListenerInterface $listener
     * @return BoundListenerInterface
     */
    public function add(BoundListenerInterface $listener): BoundListenerInterface
    {
        return $this->listeners[] = $listener;
    }

    /**
     * @param BoundListenerInterface $listener
     * @return void
     */
    public function remove(BoundListenerInterface $listener): void
    {
        $key = array_search($listener, $this->listeners, true);
        if ($key !== false) {
            unset($this->listeners[$key]);
        }
    }

    /**
     * @param object $event
     * @return iterable
     */
    public function getListenersForEvent(object $event): iterable
    {
        $output = [];
        foreach ($this->listeners as $listener) {
            if ($listener->listensFor($event)) {
                $output[] = $listener;
            }
        }
        return $output;
    }
}