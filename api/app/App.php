<?php

namespace App;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Psr\Container\ContainerInterface;
use Sicet7\Database\ConnectionFactory;
use Sicet7\Database\Interfaces\ConnectionFactoryInterface;
use Sicet7\Database\WrappedConnection;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;
use Doctrine\DBAL\Driver\Connection as DoctrineConnectionInterface;

class App implements PluginInterface
{
    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        $this->registerDatabase($source);
    }

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    private function registerDatabase(MutableDefinitionSourceHelper $source): void
    {
        $source->env('env.database.dsn', 'DATABASE_DSN');
        $source->factory(Configuration::class, function () {
            return new Configuration();
        });
        $source->factory(EventManager::class, function () {
            return new EventManager();
        });
        $source->factory(ConnectionFactory::class, function (
            ContainerInterface $container,
            Configuration $configuration,
            EventManager $eventManager
        ): ConnectionFactory {
            return new ConnectionFactory(
                $container->get('env.database.dsn'),
                $configuration,
                $eventManager
            );
        });
        $source->reference(ConnectionFactoryInterface::class, ConnectionFactory::class);
        $source->factory(WrappedConnection::class, function (ConnectionFactoryInterface $connectionFactory) {
            return new WrappedConnection($connectionFactory);
        });
        $source->reference(DoctrineConnectionInterface::class, WrappedConnection::class);
    }
}