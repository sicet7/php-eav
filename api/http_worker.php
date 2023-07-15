<?php

use Sicet7\Plugin\Plugin;
use Sicet7\Server\HttpWorker;

require_once __DIR__ . '/bootstrap.php';

$container = Plugin::init();

$container->get(HttpWorker::class)->run();