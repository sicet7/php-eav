<?php

namespace Sicet7\Migrations;

use Doctrine\DBAL\Connection;
use Doctrine\Migrations\Configuration\Configuration;
use Doctrine\Migrations\Configuration\Connection\ExistingConnection;
use Doctrine\Migrations\Configuration\Migration\ExistingConfiguration;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\MetadataStorageConfiguration;
use Doctrine\Migrations\Metadata\Storage\TableMetadataStorageConfiguration;
use Doctrine\Migrations\Tools\Console\ConsoleRunner;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sicet7\Base\Plugin\BootablePluginInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Symfony\Component\Console\Application;

final readonly class MigrationsPlugin implements PluginInterface, BootablePluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->factory(ExistingConnection::class, function (Connection $connection): ExistingConnection {
            return new ExistingConnection($connection);
        });

        $source->factory(TableMetadataStorageConfiguration::class, function (): TableMetadataStorageConfiguration {
            return new TableMetadataStorageConfiguration();
        });
        $source->reference(MetadataStorageConfiguration::class, TableMetadataStorageConfiguration::class);
        $source->factory(Configuration::class, function (MetadataStorageConfiguration $metadataStorageConfiguration): Configuration {
            $config = new Configuration();
            $config->setMetadataStorageConfiguration($metadataStorageConfiguration);
            return $config;
        });
        $source->factory(ExistingConfiguration::class, function (Configuration $configuration): ExistingConfiguration {
            return new ExistingConfiguration($configuration);
        });
        $source->factory(DependencyFactory::class, function (
            ExistingConnection $existingConnection,
            ExistingConfiguration $existingConfiguration,
            ContainerInterface $container
        ): DependencyFactory {
            return DependencyFactory::fromConnection(
                $existingConfiguration,
                $existingConnection,
                $container->has(LoggerInterface::class) ? $container->get(LoggerInterface::class) : null
            );
        });
    }

    /**
     * @param ContainerInterface $container
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function boot(ContainerInterface $container): void
    {
        if ($container->has(Application::class)) {
            ConsoleRunner::addCommands(
                $container->get(Application::class),
                $container->get(DependencyFactory::class)
            );
        }
    }
}