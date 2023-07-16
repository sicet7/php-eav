<?php

namespace Sicet7\Console;

use Psr\Container\ContainerInterface;
use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

final readonly class ConsolePlugin implements PluginInterface
{
    /**
     * @param string[] $commandMap
     */
    public function __construct(private array $commandMap = [])
    {
        array_map(fn(string $key) => $key, array_keys($this->commandMap));
        array_map(fn(string $value) => $value, $this->commandMap);
    }

    /**
     * @param ReflectorInterface $reflector
     * @return string[]
     */
    public static function makeCommandMap(ReflectorInterface $reflector): array
    {
        $output = [];
        foreach ($reflector->reflectAllClasses() as $class) {
            if (!$class->isSubclassOf(Command::class)) {
                continue;
            }
            $asCommandAttributes = $class->getAttributesByInstance(AsCommand::class);
            if (empty($asCommandAttributes)) {
                continue;
            }
            $asCommandAttribute = $asCommandAttributes[array_key_first($asCommandAttributes)];
            $attributeClassName = $asCommandAttribute->getClass()->getName();
            $attributeArguments = $asCommandAttribute->getArguments();
            if (empty($attributeArguments)) {
                $attributeInstance = new $attributeClassName();
            } else {
                $attributeInstance = new $attributeClassName(...$attributeArguments);
            }
            /** @var AsCommand $attributeInstance */
            $output[$attributeInstance->name] = $class->getName();
        }
        return $output;
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $commandMap = $this->commandMap;
        $source->factory(ContainerCommandLoader::class, function (
            ContainerInterface $container
        ) use ($commandMap) : ContainerCommandLoader {
            return new ContainerCommandLoader($container, $commandMap);
        });
        $source->reference(CommandLoaderInterface::class, ContainerCommandLoader::class);
        $source->factory(Application::class, function (
            ContainerInterface $container,
            CommandLoaderInterface $commandLoader
        ): Application {
            $appName = 'UNKNOWN';
            $appVersion = 'UNKNOWN';
            if ($container->has('app.name') && is_string($container->get('app.name'))) {
                $appName = $container->get('app.name');
            }
            if ($container->has('app.version') && is_string($container->get('app.version'))) {
                $appVersion = $container->get('app.version');
            }
            $app = new Application($appName, $appVersion);
            $app->setCommandLoader($commandLoader);
            if ($container->has(EventDispatcherInterface::class)) {
                $app->setDispatcher($container->get(EventDispatcherInterface::class));
            }
            return $app;
        });
    }
}