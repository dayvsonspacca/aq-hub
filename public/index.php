<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use AqHub\Shared\Infrastructure\Http\Application;
use DI\ContainerBuilder;

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $containerBuilder->build();

$application = $container->get(Application::class);

$controllersPath = __DIR__ . '/../config/controllers.php';
$controllersArray = require $controllersPath;

$application->registerControllers($controllersArray);

$application->handle();