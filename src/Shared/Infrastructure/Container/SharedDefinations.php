<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Shared\Infrastructure\Cache\{SymfonyCacheAdapter, SymfonyFileSystemCacheFactory};
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
                Env::class => factory([Env::class, 'instance'])->parameter('env', $_SERVER),
                Application::class => autowire(),
                Connection::class => factory([Connection::class, 'instance'])->parameter('env', get(Env::class))
            ],
            self::loggers(),
            self::caches()
        );
    }

    public static function loggers(): array
    {
        return [
            'Logger.Api.Errors' => FileLoggerFactory::create('AQHUB_API_ERRORS', 'api_errors.log', Level::Error)
        ];
    }

    public static function caches(): array
    {
        return [
            'Cache.Players' => factory(function () {
                $symfonyCache = SymfonyFileSystemCacheFactory::create('players', 0);
                return new SymfonyCacheAdapter($symfonyCache);
            }),
            'Cache.Armors' => factory(function () {
                $symfonyCache = SymfonyFileSystemCacheFactory::create('armors', 60);
                return new SymfonyCacheAdapter($symfonyCache);
            }),
            'Cache.Capes' => factory(function () {
                $symfonyCache = SymfonyFileSystemCacheFactory::create('capes', 60);
                return new SymfonyCacheAdapter($symfonyCache);
            }),
        ];
    }
}
