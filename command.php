<?php

require __DIR__ . '/vendor/autoload.php';

use AqHub\Items\Application\Weapon\AddWeapon;
use AqHub\Items\Infrastructure\Repositories\Sql\SqliteWeaponRepository;
use AqHub\Items\Infrastructure\Commands\AddItemCommand;
use AqHub\Items\Infrastructure\Commands\MineCharpageItemsCommand;
use AqHub\Shared\Infrastructure\Database\Connection;
use Symfony\Component\Console\Application;

$db = Connection::connect(
    path: __DIR__ . '/database/db.sqlite'
);

if ($db->isError()) {
    echo $db->getMessage() . PHP_EOL;
    exit(0);
}

$db = $db->getData();

$addWeapon = new AddWeapon(
    new SqliteWeaponRepository($db)
);

$application = new Application();

$application->add(new AddItemCommand($addWeapon));
$application->add(new MineCharpageItemsCommand($addWeapon));

$application->run();
