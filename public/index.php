<?php

declare(strict_types=1);

define('ROOT_PATH', __DIR__ . '/../');
define('LOGS_PATH', ROOT_PATH . 'logs/');

require ROOT_PATH . 'vendor/autoload.php';

use AqHub\Shared\Infrastructure\Container\Container;
use AqHub\Shared\Infrastructure\Http\Application;

$container = Container::build();
$application = $container->get(Application::class);
$application->handle();
