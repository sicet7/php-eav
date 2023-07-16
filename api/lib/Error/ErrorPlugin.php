<?php

namespace Sicet7\Error;

use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Sicet7\Base\Plugin\BootablePluginInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;

class ErrorPlugin implements BootablePluginInterface
{
    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function boot(ContainerInterface $container): void
    {
        $container->get(ErrorHandler::class);
    }

    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->autowire(ErrorHandler::class, ErrorHandler::class);
    }
}