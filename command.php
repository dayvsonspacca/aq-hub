<?php

require __DIR__ . '/vendor/autoload.php';

use AqHub\Items\Infrastructure\Console\MineAllPlayersItemsCommand;
use AqHub\Items\Infrastructure\Console\MineCharpageItemsCommand;
use AqHub\Player\Infrastructure\Console\MinePlayersNameCommand;
use Symfony\Component\Console\Application;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/container.php');
$container = $builder->build();

$application = new Application();

$application->add($container->get(MineCharpageItemsCommand::class));
$application->add($container->get(MineAllPlayersItemsCommand::class));
$application->add($container->get(MinePlayersNameCommand::class));

$application->run();
