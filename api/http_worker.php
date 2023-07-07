<?php

use Sicet7\Plugin\Container\ContainerBuilder;
use Sicet7\Server\HttpWorker;

require_once __DIR__ . '/bootstrap.php';

$container = ContainerBuilder::build();

$container->get(HttpWorker::class)->run();