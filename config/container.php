<?php

declare(strict_types=1);

use AqHub\Player\Infrastructure\Container\PlayerContainerRegistry;
use AqHub\Items\Infrastructure\Container\ItemsContainerRegistry;
use AqHub\Shared\Infrastructure\Database\Connection;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

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
        },
        LoggerInterface::class => function (): Logger {
            $logger = new Logger('aqhub_app');
            $logFilePath = __DIR__ . '/../logs/app.log';

            $logger->pushHandler(new StreamHandler($logFilePath, Level::Warning));
            return $logger;
        },

    ],
    ItemsContainerRegistry::build(),
    PlayerContainerRegistry::build()
);
