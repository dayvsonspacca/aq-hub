<?php

require __DIR__ . '/vendor/autoload.php';

use AqWiki\Items\Application\Weapon\AddWeapon;
use AqWiki\Items\Infrastructure\Repositories\Sql\SqliteWeaponRepository;
use AqWiki\Items\Infrastructure\Commands\AddItemCommand;
use AqWiki\Shared\Infrastructure\Database\Connection;
use Symfony\Component\Console\Application;

$db = Connection::connect(
    path: __DIR__ . '/database/db.sqlite'
);

if ($db->isError()) {
    echo $db->getMessage() . PHP_EOL;
    exit(0);
}

$db = $db->getData();

$application = new Application();

$application->add(new AddItemCommand(
    new AddWeapon(
        new SqliteWeaponRepository($db)
    )
));

$application->run();
