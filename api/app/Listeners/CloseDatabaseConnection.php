<?php

namespace App\Listeners;

use Sicet7\Database\ClosableConnection;
use Sicet7\Events\Attributes\Listens;
use Sicet7\Events\Interfaces\EventListenerInterface;
use Sicet7\Plugin\Attributes\Autowire;
use Sicet7\Server\Events\InternalServerError;
use Sicet7\Server\Events\PostDispatch;
use Sicet7\Server\Events\RoadRunnerCommunicationsError;

#[Listens(PostDispatch::class)]
#[Listens(RoadRunnerCommunicationsError::class)]
#[Listens(InternalServerError::class)]
#[Autowire]
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