<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Shared\Infrastructure\Env\Env;
use AqHub\Shared\Infrastructure\Http\Application;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

use function DI\autowire;
use function DI\factory;

class SharedDefinations implements Definations
{
    public static function getDefinitions(): array
    {
        return [
            Env::class => factory([Env::class, 'instance']),
            Application::class => autowire(),
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
                $logger->pushHandler(new StreamHandler(LOGS_PATH . '/errors.log', Level::Warning));
                return $logger;
            },
        ];
    }
}
