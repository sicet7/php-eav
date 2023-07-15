<?php

namespace Sicet7\Plugin;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\Events\Attributes\Listens;
use Sicet7\Events\EventDispatcher;
use Sicet7\Events\Interfaces\ListenerContainerInterface;
use Sicet7\Events\ListenerContainer;
use Sicet7\Events\ResolvableListener;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;

final readonly class Events implements PluginInterface
{
    /**
     * @param ReflectorInterface $reflector
     */
    public function __construct(
        public ReflectorInterface $reflector
    ) {
    }

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        $listens = [];
        foreach ($this->reflector->reflectAllClasses() as $class) {
            foreach ($class->getAttributesByInstance(Listens::class) as $attribute) {
                $attributeClassName = $attribute->getClass()->getName();
                $args = $attribute->getArguments();
                /** @var Listens $attributeInstance */
                $attributeInstance = (empty($args) ? new $attributeClassName() : new $attributeClassName(...$args));
                $listens[$attributeInstance->eventFqcn][] = $class->getName();
            }
        }
        $source->factory(ListenerContainer::class, function (ContainerInterface $container) use ($listens) {
            $instance = new ListenerContainer();
            foreach ($listens as $eventFqcn => $listenersFqcns) {
                foreach ($listenersFqcns as $listenerFqcn) {
                    $instance->add(new ResolvableListener($eventFqcn, $container, $listenerFqcn));
                }
            }
            return $instance;
        });
        $source->reference(ListenerContainerInterface::class, ListenerContainer::class);
        $source->reference(ListenerProviderInterface::class, ListenerContainer::class);
        $source->factory(EventDispatcher::class, function (ListenerProviderInterface $listenerProvider) {
            return new EventDispatcher($listenerProvider);
        });
        $source->reference(EventDispatcherInterface::class, EventDispatcher::class);
    }
}