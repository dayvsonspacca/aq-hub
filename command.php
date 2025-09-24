<?php

require __DIR__ . '/vendor/autoload.php';

use AqHub\Items\Infrastructure\Commands\MineAllPlayersItemsCommand;
use AqHub\Items\Infrastructure\Commands\MineCharpageItemsCommand;
use Symfony\Component\Console\Application;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config.php');
$container = $builder->build();

$application = new Application();

$application->add($container->get(MineCharpageItemsCommand::class));
$application->add($container->get(MineAllPlayersItemsCommand::class));

$application->run();
