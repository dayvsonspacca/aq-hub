<?php

require __DIR__ . '/vendor/autoload.php';

use AqWiki\Items\Infrastructure\Database\InMemory\InMemoryWeaponRepository;
use AqWiki\Items\Infrastructure\Commands\AddItemCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new AddItemCommand(
    new InMemoryWeaponRepository()
));

$application->run();
