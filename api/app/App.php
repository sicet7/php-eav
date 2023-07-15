<?php

namespace App;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver\Connection as DoctrineConnectionInterface;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Contracts\Plugin\PluginInterface;
use Sicet7\Database\ConnectionFactory;
use Sicet7\Database\Interfaces\ConnectionFactoryInterface;
use Sicet7\Database\WrappedConnection;

class App implements PluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $this->registerLogging($source);
        $this->registerDatabase($source);
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    private function registerLogging(MutableDefinitionSourceInterface $source): void
    {
        $source->decorate(Logger::class, function (Logger $logger, ContainerInterface $container) {
            return $logger;
        });
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    private function registerDatabase(MutableDefinitionSourceInterface $source): void
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