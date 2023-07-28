<?php

namespace Sicet7\Database\Events;

use Sicet7\Database\ClosableConnection;
use Sicet7\Events\Interfaces\EventListenerInterface;
use Sicet7\Server\Events\PostDispatch;

readonly class CloseDatabaseConnection implements EventListenerInterface
{
    /**
     * @param ClosableConnection $connection
     */
    public function __construct(private ClosableConnection $connection)
    {
    }

    /**
     * @param object $event
     * @return void
     */
    public function execute(object $event): void
    {
        /** @var PostDispatch $event */
        $this->connection->closeConnection();
    }
}