<?php

namespace Sicet7\Plugin;

use Monolog\Logger;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;

final readonly class Log implements PluginInterface
{

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        $source->factory(Logger::class, function () {
            return new Logger('Application');
        });
    }
}