<?php

namespace Sicet7\Events\Interfaces;

use Psr\EventDispatcher\ListenerProviderInterface;

interface ListenerContainerInterface extends ListenerProviderInterface
{
    /**
     * @param BoundListenerInterface $listener
     * @return BoundListenerInterface
     */
    public function add(BoundListenerInterface $listener): BoundListenerInterface;

    /**
     * @param BoundListenerInterface $listener
     * @return void
     */
    public function remove(BoundListenerInterface $listener): void;
}