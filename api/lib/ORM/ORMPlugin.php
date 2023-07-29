<?php

namespace Sicet7\ORM;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration as ORMConfiguration;
use Doctrine\DBAL\Configuration as DBALConfiguration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Container\ContainerInterface;
use Sicet7\Base\Plugin\BootablePluginInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Console\Application;

final readonly class ORMPlugin implements PluginInterface, BootablePluginInterface
{
    public const PATHS_KEY = 'orm.plugin.paths';
    public const DEV_MODE_KEY = 'orm.plugin.dev.mode';
    public const PROXY_PATH_KEY = 'orm.plugin.proxy.path';

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->factory(ORMConfiguration::class, function (ContainerInterface $container): ORMConfiguration {
            $paths = [];
            $isDevMode = false;
            $proxyDir = null;
            $cache = null;

            if ($container->has(self::PATHS_KEY) && is_array($container->get(self::PATHS_KEY))) {
                $paths = $container->get(self::PATHS_KEY);
            }

            if ($container->has(self::DEV_MODE_KEY) && is_bool($container->get(self::DEV_MODE_KEY))) {
                $isDevMode = $container->get(self::DEV_MODE_KEY);
            }

            if ($container->has(self::PROXY_PATH_KEY) && is_string($container->get(self::PROXY_PATH_KEY))) {
                $proxyDir = $container->get(self::PROXY_PATH_KEY);
            }

            if ($container->has(CacheItemPoolInterface::class)) {
                $cache = $container->get(CacheItemPoolInterface::class);
            } else {
                $cache = new ArrayAdapter();
            }

            return ORMSetup::createAttributeMetadataConfiguration(
                paths: $paths,
                isDevMode: $isDevMode,
                proxyDir: $proxyDir,
                cache: $cache
            );
        });
        $source->reference(DBALConfiguration::class, ORMConfiguration::class);
        $source->factory(EntityManager::class, function (
            Connection $connection,
            ORMConfiguration $configuration,
            EventManager $eventManager
        ): EntityManager {
            return new EntityManager($connection, $configuration, $eventManager);
        });
        $source->reference(EntityManagerInterface::class, EntityManager::class);
        $source->factory(SingleManagerProvider::class, function (EntityManagerInterface $entityManager): SingleManagerProvider {
            return new SingleManagerProvider($entityManager);
        });
        $source->reference(EntityManagerProvider::class, SingleManagerProvider::class);
    }

    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function boot(ContainerInterface $container): void
    {
        if ($container->has(Application::class)) {
            ConsoleRunner::addCommands(
                $container->get(Application::class),
                $container->get(EntityManagerProvider::class)
            );
        }
    }
}