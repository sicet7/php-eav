<?php

namespace Sicet7\Base\Plugin;

interface MutableDefinitionSourceInterface
{
    /**
     * @param string $name
     * @param callable|array|string $factory
     * @param array $parameters
     * @return void
     */
    public function factory(string $name, callable|array|string $factory, array $parameters = []): void;

    /**
     * @param string $name
     * @param string $target
     * @return void
     */
    public function reference(string $name, string $target): void;

    /**
     * @param string $name
     * @param callable|array|string $factory
     * @param array $parameters
     * @return void
     */
    public function decorate(string $name, callable|array|string $factory, array $parameters = []): void;

    /**
     * @param string $name
     * @param string $expression
     * @return void
     */
    public function string(string $name, string $expression): void;

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function value(string $name, mixed $value): void;

    /**
     * @param string $name
     * @param string $class
     * @return void
     */
    public function object(string $name, string $class): void;

    /**
     * @param string $name
     * @param array $values
     * @param bool $override
     * @return void
     */
    public function array(string $name, array $values, bool $override = false): void;

    /**
     * @param string $name
     * @param string $variableName
     * @param mixed|null $defaultValue
     * @return void
     */
    public function env(string $name, string $variableName, mixed $defaultValue = null): void;

    /**
     * @param string $name
     * @param string $class
     * @return void
     */
    public function autowire(string $name, string $class): void;
}