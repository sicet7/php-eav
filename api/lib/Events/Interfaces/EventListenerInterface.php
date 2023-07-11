<?php

namespace Sicet7\Events\Interfaces;

interface EventListenerInterface
{
    /**
     * @param object $event
     * @return void
     */
    public function execute(object $event): void;
}