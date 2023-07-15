<?php

namespace Sicet7\Contracts\Plugin;

interface PluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void;
}