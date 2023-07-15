<?php

namespace App\Listeners;

use Sicet7\Database\WrappedConnection;
use Sicet7\Events\Attributes\Listens;
use Sicet7\Events\Interfaces\EventListenerInterface;
use Sicet7\Server\Events\PostDispatch;

#[Listens(PostDispatch::class)]
readonly class CloseDatabaseConnection implements EventListenerInterface
{
    /**
     * @param WrappedConnection $connection
     */
    public function __construct(private WrappedConnection $connection)
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