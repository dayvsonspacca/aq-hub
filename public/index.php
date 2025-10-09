<?php

require __DIR__ . '/../vendor/autoload.php';

use AqHub\Player\Infrastructure\Http\Controllers\PlayerController;
use AqHub\Shared\Infrastructure\Http\Application;
use DI\ContainerBuilder;
use Psr\Log\LoggerInterface;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/../config/container.php');
$container = $builder->build();

$application = new Application($container, $container->get(LoggerInterface::class));
$application->registerControllers([
    PlayerController::class
]);

$application->handle();