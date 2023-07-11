<?php

namespace Sicet7\Events\Interfaces;

interface BoundListenerInterface extends EventListenerInterface
{
    /**
     * @param object $event
     * @return bool
     */
    public function listensFor(object $event): bool;
}