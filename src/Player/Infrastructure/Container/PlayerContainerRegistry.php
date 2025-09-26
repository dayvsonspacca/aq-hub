<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Container;

use AqHub\Player\Application\UseCases\{AddPlayer, FindAllPlayers, MarkAsMined};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Infrastructure\Console\MinePlayersNameCommand;
use AqHub\Player\Infrastructure\Http\Controllers\PlayerController;
use AqHub\Player\Infrastructure\Repositories\Sql\SqlPlayerRepository;
use AqHub\Shared\Infrastructure\Container\ContainerRegistry;
use AqHub\Shared\Infrastructure\Database\Connection;

use function DI\{autowire, get};

class PlayerContainerRegistry implements ContainerRegistry
{
    public static function build(): array
    {
        return array_merge(
            self::registerRepositories(),
            self::registerUseCases(),
            self::registerCommands(),
            self::registerControllers()
        );
    }

    public static function registerRepositories(): array
    {
        return [
            SqlPlayerRepository::class => autowire()->constructor(get(Connection::class)),
            PlayerRepository::class => autowire(SqlPlayerRepository::class)
        ];
    }

    public static function registerCommands(): array
    {
        return [
            MinePlayersNameCommand::class => autowire()
        ];
    }

    public static function registerUseCases(): array
    {
        return [
            AddPlayer::class => autowire(),
            FindAllPlayers::class => autowire(),
            MarkAsMined::class => autowire()
        ];
    }

    public static function registerControllers(): array
    {
        return [
            PlayerController::class => autowire()
        ];
    }
}
