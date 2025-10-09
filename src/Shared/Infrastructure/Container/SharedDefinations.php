<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Shared\Infrastructure\Env\Env;
use AqHub\Shared\Infrastructure\Http\Application;
use AqHub\Shared\Infrastructure\Log\FileLoggerFactory;
use Monolog\Level;

use function DI\autowire;
use function DI\factory;

class SharedDefinations implements Definations
{
    public static function getDefinitions(): array
    {
        return array_merge(
            [
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
                }
            ],
            self::loggers(),
        );
    }

    public static function loggers(): array
    {
        return [
            'Logger.Api.Errors' => FileLoggerFactory::create('AQHUB_API_ERRORS', 'api_errors.log', Level::Error)
        ];
    }
}
