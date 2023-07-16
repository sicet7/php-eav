<?php

namespace Sicet7\Database;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\Connection as DoctrineConnectionInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Psr\Container\ContainerInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Sicet7\Database\Interfaces\ConnectionFactoryInterface;

final readonly class DatabasePlugin implements PluginInterface
{
    public const DATABASE_DSN_KEY = 'database.plugin.dsn';
    public const DATABASE_DSN_SCHEMA_KEY = 'database.plugin.dsn.schema.mapping';

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->factory(Configuration::class, function () {
            return new Configuration();
        });
        $source->factory(EventManager::class, function () {
            return new EventManager();
        });
        $source->value(self::DATABASE_DSN_SCHEMA_KEY, [
            'mysql' => 'pdo_mysql',
            'postgres' => 'pdo_pgsql',
            'sqlite' => 'pdo_sqlite',
            'sqlsrv' => 'pdo_sqlsrv',
        ]);
        $source->factory(ClosableConnection::class, function (
            Configuration $configuration,
            EventManager $eventManager,
            ContainerInterface $container
        ): ClosableConnection {
            $parser = new DsnParser($container->get(self::DATABASE_DSN_SCHEMA_KEY));
            $params = $parser->parse($container->get(self::DATABASE_DSN_KEY));
            $params['wrapperClass'] = ClosableConnection::class;
            return DriverManager::getConnection($params, $configuration, $eventManager);
        });
        $source->reference(Connection::class, ClosableConnection::class);
    }
}