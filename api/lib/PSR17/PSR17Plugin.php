<?php

namespace Sicet7\PSR17;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;
use Sicet7\Contracts\Plugin\MutableDefinitionSourceInterface;
use Sicet7\Contracts\Plugin\PluginInterface;

final readonly class PSR17Plugin implements PluginInterface
{
    /**
     * @param MutableDefinitionSourceInterface $source
     * @return void
     */
    public function register(MutableDefinitionSourceInterface $source): void
    {
        $source->object(Psr17Factory::class, Psr17Factory::class);
        $source->reference(RequestFactoryInterface::class, Psr17Factory::class);
        $source->reference(ResponseFactoryInterface::class, Psr17Factory::class);
        $source->reference(ServerRequestFactoryInterface::class, Psr17Factory::class);
        $source->reference(StreamFactoryInterface::class, Psr17Factory::class);
        $source->reference(UploadedFileFactoryInterface::class, Psr17Factory::class);
        $source->reference(UriFactoryInterface::class, Psr17Factory::class);
    }
}