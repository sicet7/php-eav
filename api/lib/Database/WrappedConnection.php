<?php

namespace Sicet7\Database;

use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\Driver\Exception as DoctrineDriverException;
use Doctrine\DBAL\Driver\Result;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\ParameterType;
use Sicet7\Database\Interfaces\ConnectionFactoryInterface;

/**
 * @method object getNativeConnection()
 */
final class WrappedConnection implements ConnectionInterface
{
    /**
     * @var ConnectionInterface|null
     */
    private ?ConnectionInterface $connection = null;

    /**
     * @param ConnectionFactoryInterface $connectionFactory
     */
    public function __construct(private readonly ConnectionFactoryInterface $connectionFactory)
    {
    }

    /**
     * @return void
     */
    public function closeConnection(): void
    {
        $this->connection = null;
    }

    /**
     * @return void
     */
    private function openConnection(): void
    {
        if ($this->connection === null) {
            $this->connection = $this->connectionFactory->build();
        }
    }

    /**
     * @param string $sql
     * @return Statement
     * @throws DoctrineDriverException
     */
    public function prepare(string $sql): Statement
    {
        $this->openConnection();
        return $this->connection->prepare($sql);
    }

    /**
     * @param string $sql
     * @return Result
     * @throws DoctrineDriverException
     */
    public function query(string $sql): Result
    {
        $this->openConnection();
        return $this->connection->query($sql);
    }

    /**
     * @param $value
     * @param $type
     * @return mixed
     */
    public function quote($value, $type = ParameterType::STRING)
    {
        $this->openConnection();
        return $this->connection->quote($value, $type);
    }

    /**
     * @param string $sql
     * @return int
     * @throws DoctrineDriverException
     */
    public function exec(string $sql): int
    {
        $this->openConnection();
        return $this->connection->exec($sql);
    }

    /**
     * @param $name
     * @return false|int|string
     * @throws DoctrineDriverException
     */
    public function lastInsertId($name = null)
    {
        $this->openConnection();
        return $this->connection->lastInsertId($name);
    }

    /**
     * @return bool
     * @throws DoctrineDriverException
     */
    public function beginTransaction()
    {
        $this->openConnection();
        return $this->connection->beginTransaction();
    }

    /**
     * @return bool
     * @throws DoctrineDriverException
     */
    public function commit()
    {
        $this->openConnection();
        return $this->connection->commit();
    }

    /**
     * @return bool
     * @throws DoctrineDriverException
     */
    public function rollBack()
    {
        $this->openConnection();
        return $this->connection->rollBack();
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $this->openConnection();
        if (empty($arguments)) {
            return $this->connection->{$name}();
        }
        return $this->connection->{$name}(...$arguments);
    }
}