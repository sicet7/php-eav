<?php

namespace App;

use Doctrine\Migrations\Configuration\Configuration as MigrationConfiguration;
use Monolog\ErrorHandler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Sicet7\Database\DatabasePlugin;
use Sicet7\ORM\ORMPlugin;

class App implements PluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $this->registerErrorHandling($source);
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
            //TODO: register other logging handlers here.
            return $logger;
        });
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    private function registerDatabase(MutableDefinitionSourceInterface $source): void
    {
        $source->env(DatabasePlugin::DSN_KEY, 'DATABASE_DSN');
        $source->value(ORMPlugin::PATHS_KEY, dirname(__DIR__) . '/app/Database/Entities');
        $source->decorate(MigrationConfiguration::class, function (MigrationConfiguration $config, ContainerInterface $container): MigrationConfiguration {
            $config->addMigrationsDirectory('Migrations', dirname(__DIR__) . '/migrations');
            return $config;
        });
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    private function registerErrorHandling(MutableDefinitionSourceInterface $source): void
    {
        $source->decorate(ErrorHandler::class, function (ErrorHandler $handler, ContainerInterface $container) {
            $handler->registerErrorHandler();
            $handler->registerExceptionHandler();
            $handler->registerFatalHandler(null, 100);
            $container->get(LoggerInterface::class)->info('Error Handler Registered!');
            return $handler;
        });
    }
}