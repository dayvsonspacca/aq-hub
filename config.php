<?php

declare(strict_types=1);

use AqHub\Player\Infrastructure\Container\PlayerContainerRegistry;
use AqHub\Items\Infrastructure\Container\ItemsContainerRegistry;
use AqHub\Shared\Infrastructure\Database\Connection;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

return array_merge(
    [
        Connection::class => function () {
            $db = Connection::connect(
                path: __DIR__ . '/database/db.sqlite'
            );

            if ($db->isError()) {
                echo $db->getMessage() . PHP_EOL;
                exit(0);
            }

            return $db->getData();
        },
        Environment::class => $twig
    ],
    ItemsContainerRegistry::build(),
    PlayerContainerRegistry::build()
);
