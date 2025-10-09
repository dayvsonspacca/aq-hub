<?php

declare(strict_types=1);

define('ROOT_PATH', __DIR__ . '/../');
define('LOGS_PATH', ROOT_PATH . 'logs/');

require ROOT_PATH . 'vendor/autoload.php';

use AqHub\Shared\Infrastructure\Http\Application;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

$application = $container->get(Application::class);
$application->handle();
