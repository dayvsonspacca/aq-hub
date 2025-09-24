<?php

declare(strict_types=1);

use AqHub\Player\Infrastructure\Container\PlayerContainerRegistry;
use AqHub\Items\Infrastructure\Container\ItemsContainerRegistry;
use AqHub\Shared\Infrastructure\Database\Connection;

return array_merge(
    [
        Connection::class => function () {
            $db = Connection::connect(
                host: 'db',
                dbname: 'aqhub',
                username: 'aqhub',
                password: 'aqhub',
                port: 5432
            );

            if ($db->isError()) {
                echo '[DB ERROR] ' . $db->getMessage() . PHP_EOL;
                exit(1);
            }

            return $db->getData();
        }
    ],
    ItemsContainerRegistry::build(),
    PlayerContainerRegistry::build()
);
