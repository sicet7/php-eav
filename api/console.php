<?php

use Sicet7\Plugin\Container\ContainerBuilder;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/bootstrap.php';

$container = ContainerBuilder::build();

exit($container->get(Application::class)->run());