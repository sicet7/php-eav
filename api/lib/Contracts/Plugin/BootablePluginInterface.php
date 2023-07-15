<?php

namespace Sicet7\Contracts\Plugin;

use Psr\Container\ContainerInterface;

interface BootablePluginInterface extends PluginInterface
{
    /**
     * @param ContainerInterface $container
     * @return void
     */
    public function boot(ContainerInterface $container): void;
}