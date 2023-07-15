<?php

namespace Sicet7\Plugin;

use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Contracts\Plugin\PluginInterface;
use Sicet7\Plugin\Attributes\Autowire;

final readonly class AutowireAttributeLoaderPlugin implements PluginInterface
{
    /**
     * @param ReflectorInterface $reflector
     */
    public function __construct(private ReflectorInterface $reflector)
    {
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        foreach ($this->reflector->reflectAllClasses() as $class) {
            if(count($class->getAttributesByInstance(Autowire::class)) === 0) {
                continue;
            }
            $source->autowire($class->getName(), $class->getName());
        }
    }
}