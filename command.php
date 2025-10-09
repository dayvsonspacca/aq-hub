<?php
declare(strict_types=1);

use Dotenv\Dotenv;

define('ROOT_PATH', __DIR__ . '/');
define('LOGS_PATH', ROOT_PATH . 'logs/');

require ROOT_PATH . 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

use AqHub\Items\Infrastructure\Console\{MineAllPlayersItemsCommand, MineCharpageItemsCommand};
use AqHub\Player\Infrastructure\Console\MinePlayersNameCommand;
use AqHub\Shared\Infrastructure\Container\Container;
use Symfony\Component\Console\Application;

$container = Container::build();
$application = new Application();

$application->add($container->get(MineCharpageItemsCommand::class));
$application->add($container->get(MineAllPlayersItemsCommand::class));
$application->add($container->get(MinePlayersNameCommand::class));

$application->run();
