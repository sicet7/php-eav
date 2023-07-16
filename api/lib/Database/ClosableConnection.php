<?php

namespace Sicet7\Database;

use Doctrine\DBAL\Connection;

class ClosableConnection extends Connection
{
    /**
     * @return bool
     */
    public function closeConnection(): bool
    {
        if ($this->_conn === null) {
            return false;
        }
        $this->_conn = null;
        return true;
    }
}