<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Shared\Infrastructure\Env\Env;
use AqHub\Shared\Infrastructure\Http\Application;
use AqHub\Shared\Infrastructure\Log\FileLoggerFactory;

use function DI\{autowire, factory, get};

use Monolog\Level;

class SharedDefinations implements Definations
{
    public static function getDefinitions(): array
    {
        return array_merge(
            [
                Env::class => factory([Env::class, 'instance']),
                Application::class => autowire(),
                Connection::class => factory([Connection::class, 'instance'])->parameter('env', get(Env::class))
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
