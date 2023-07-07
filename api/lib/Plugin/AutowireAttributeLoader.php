<?php

namespace Sicet7\Plugin;

use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\Plugin\Container\Attributes\Autowire;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;

final readonly class AutowireAttributeLoader implements PluginInterface
{
    /**
     * @param ReflectorInterface $reflector
     */
    public function __construct(private ReflectorInterface $reflector)
    {
    }

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        foreach ($this->reflector->reflectAllClasses() as $class) {
            if(count($class->getAttributesByInstance(Autowire::class)) === 0) {
                continue;
            }
            $source->autowire($class->getName(), $class->getName());
        }
    }
}