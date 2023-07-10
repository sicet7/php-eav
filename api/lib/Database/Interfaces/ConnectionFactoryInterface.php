<?php

namespace Sicet7\Database\Interfaces;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;

interface ConnectionFactoryInterface
{
    /**
     * @return ConnectionInterface
     */
    public function build(): ConnectionInterface;
}