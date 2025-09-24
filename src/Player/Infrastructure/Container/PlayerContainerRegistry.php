<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Container;

use AqHub\Player\Infrastructure\Repositories\Sql\SqlitePlayerRepository;
use AqHub\Shared\Infrastructure\Container\ContainerRegistry;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Shared\Infrastructure\Database\Connection;
use AqHub\Player\Application\UseCases\AddPlayer;

use function DI\autowire;
use function DI\get;

class PlayerContainerRegistry implements ContainerRegistry
{
    public static function build(): array
    {
        return array_merge(
            self::registerRepositories(),
            self::registerUseCases(),
            self::registerCommands()
        );
    }

    public static function registerRepositories(): array
    {
        return [
            SqlitePlayerRepository::class => autowire()->constructor(get(Connection::class)),
            PlayerRepository::class => autowire(SqlitePlayerRepository::class)
        ];
    }

    public static function registerCommands(): array
    {
        return [];
    }

    public static function registerUseCases(): array
    {
        return [
            AddPlayer::class => autowire()
        ];
    }
}
