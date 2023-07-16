<?php

namespace Sicet7\Log;

use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Sicet7\Base\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Base\Plugin\PluginInterface;
use Spiral\Goridge\RPC\RPCInterface;

final readonly class LogPlugin implements PluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->factory(Logger::class, function (ContainerInterface $container) {
            $logger = new Logger('app');
            try {
                $logger->pushHandler($container->get(RoadRunnerHandler::class));
            } catch (\Throwable) {
                // Do nothing
            }
            return $logger;
        });
        $source->reference(LoggerInterface::class, Logger::class);
        $source->factory(RoadRunnerHandler::class, function (RPCInterface $rpc, ContainerInterface $container) {
            return new RoadRunnerHandler($rpc);
        });
    }
}