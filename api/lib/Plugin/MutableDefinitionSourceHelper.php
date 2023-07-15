<?php

namespace Sicet7\Plugin;

use DI\Definition\ArrayDefinition;
use DI\Definition\ArrayDefinitionExtension;
use DI\Definition\DecoratorDefinition;
use DI\Definition\EnvironmentVariableDefinition;
use DI\Definition\Exception\InvalidDefinition;
use DI\Definition\FactoryDefinition;
use DI\Definition\ObjectDefinition;
use DI\Definition\Reference;
use DI\Definition\Source\MutableDefinitionSource;
use DI\Definition\StringDefinition;
use DI\Definition\ValueDefinition;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Plugin\Factories\AutowireFactory;

final readonly class MutableDefinitionSourceHelper implements MutableDefinitionSourceInterface
{
    public function __construct(public MutableDefinitionSource $source)
    {
    }

    /**
     * @param string $name
     * @param callable|array|string $factory
     * @param array $parameters
     * @return void
     */
    public function factory(
        string $name,
        callable|array|string $factory,
        array $parameters = []
    ): void {
        $def = new FactoryDefinition($name, $factory, $parameters);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param string $target
     * @return void
     */
    public function reference(
        string $name,
        string $target
    ): void {
        $def = new Reference($target);
        $def->setName($name);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param callable|array|string $factory
     * @param array $parameters
     * @return void
     * @throws InvalidDefinition
     */
    public function decorate(
        string $name,
        callable|array|string $factory,
        array $parameters = []
    ): void {
        $def = new DecoratorDefinition($name, $factory, $parameters);
        $target = $this->source->getDefinition($name);
        if ($target !== null) {
            $def->setExtendedDefinition($target);
        }
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param string $expression
     * @return void
     */
    public function string(
        string $name,
        string $expression
    ): void {
        $def = new StringDefinition($expression);
        $def->setName($name);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function value(
        string $name,
        mixed $value
    ): void {
        $def = new ValueDefinition($value);
        $def->setName($name);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param string $class
     * @return void
     */
    public function object(
        string $name,
        string $class
    ): void {
        $def = new ObjectDefinition($name, $class);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param array $values
     * @param bool $override
     * @return void
     * @throws InvalidDefinition
     */
    public function array(
        string $name,
        array $values,
        bool $override = false
    ): void {
        $def = ($override ? new ArrayDefinition($values) : new ArrayDefinitionExtension($values));
        $def->setName($name);
        $oldDef = $this->source->getDefinition($name);
        if ($def instanceof ArrayDefinitionExtension && $oldDef !== null) {
            $def->setExtendedDefinition($oldDef);
        }
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param string $variableName
     * @param mixed|null $defaultValue
     * @return void
     */
    public function env(
        string $name,
        string $variableName,
        mixed $defaultValue = null
    ): void {
        $optional = func_num_args() === 3;
        $def = new EnvironmentVariableDefinition(
            $variableName,
            $optional,
            $defaultValue
        );
        $def->setName($name);
        $this->source->addDefinition($def);
    }

    /**
     * @param string $name
     * @param string $class
     * @return void
     */
    public function autowire(
        string $name,
        string $class
    ): void {
        $def = new FactoryDefinition($name, function (AutowireFactory $factory) use ($class) {
            return $factory->make($class);
        });
        $this->source->addDefinition($def);
    }
}