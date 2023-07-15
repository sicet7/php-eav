<?php

use Sicet7\Plugin\Plugin;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/bootstrap.php';

$container = Plugin::init();

exit($container->get(Application::class)->run());