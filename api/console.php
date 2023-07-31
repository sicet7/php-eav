<?php

use Sicet7\Plugin\Plugin;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/bootstrap.php';

if(
    str_ends_with(($_SERVER['SCRIPT_FILENAME'] ?? $_SERVER['SCRIPT_NAME'] ?? ''), '/eav') &&
    class_exists(Dotenv\Dotenv::class)
) {
    Dotenv\Dotenv::createImmutable(dirname(__DIR__), 'dev.env')->load();
}

$container = Plugin::init();

exit($container->get(Application::class)->run());