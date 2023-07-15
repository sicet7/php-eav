<?php

namespace Sicet7\Error;

use Monolog\ErrorHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sicet7\Contracts\Plugin\BootablePluginInterface;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;

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
        $source->factory(ErrorHandler::class, function (LoggerInterface $logger) {
            $handler = new ErrorHandler($logger);
            $handler->registerErrorHandler();
            $handler->registerExceptionHandler();
            $handler->registerFatalHandler(null, 100);
            return $handler;
        });
    }
}