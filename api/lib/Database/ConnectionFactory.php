<?php

namespace Sicet7\Database;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Connection as ConnectionInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception as DoctrineException;
use Doctrine\DBAL\Tools\DsnParser;
use Sicet7\Database\Interfaces\ConnectionFactoryInterface;

readonly class ConnectionFactory implements ConnectionFactoryInterface
{
    public const SCHEME_MAPPING = [
        'mysql' => 'pdo_mysql',
        'postgres' => 'pdo_pgsql',
        'sqlite' => 'pdo_sqlite',
        'sqlsrv' => 'pdo_sqlsrv',
    ];

    /**
     * @var string
     */
    private string $dsn;

    /**
     * @param string $dsn
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     */
    public function __construct(
        #[\SensitiveParameter]
        string $dsn,
        private ?Configuration $config = null,
        private ?EventManager $eventManager = null
    ) {
        $this->dsn = $dsn;
    }

    /**
     * @return ConnectionInterface
     * @throws DoctrineException
     */
    public function build(): ConnectionInterface
    {
        $parser = new DsnParser(self::SCHEME_MAPPING);
        $params = $parser->parse($this->dsn);
        return DriverManager::getConnection($params, $this->config, $this->eventManager);
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        $vars = get_object_vars($this);
        $vars['dsn'] = '<redacted>';
        return $vars;
    }
}