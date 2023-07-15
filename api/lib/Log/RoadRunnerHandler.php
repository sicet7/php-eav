<?php

namespace Sicet7\Log;

use Monolog\Formatter\FormatterInterface;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\LogRecord;
use Spiral\Goridge\RPC\RPCInterface;

final class RoadRunnerHandler extends AbstractProcessingHandler
{
    public function __construct(
        private readonly RPCInterface $rpc,
        int|string|Level $level = Level::Debug,
        bool $bubble = true
    ) {
        parent::__construct($level, $bubble);
    }

    /**
     * @param LogRecord $record
     * @return void
     */
    protected function write(LogRecord $record): void
    {
        $this->rpc->call('app.Log', ((string) $record->formatted) ?? '');
    }

    protected function getDefaultFormatter(): FormatterInterface
    {
        return new RoadRunnerJsonFormatter();
    }
}