<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Container;

use AqHub\Player\Application\UseCases\{AddPlayer, FindAllPlayers, MarkAsMined, PlayerUseCases};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Infrastructure\Console\MinePlayersNameCommand;
use AqHub\Player\Infrastructure\Http\Controllers\PlayerController;
use AqHub\Player\Infrastructure\Repositories\Sql\SqlPlayerRepository;
use AqHub\Shared\Infrastructure\Container\Definations;
use AqHub\Shared\Infrastructure\Database\Connection;

use function DI\{autowire, get};

class PlayerDefinations implements Definations
{
    public static function getDefinitions(): array
    {
        return array_merge(
            self::repositories(),
            self::useCases(),
            self::commands(),
            self::controllers()
        );
    }

    public static function repositories(): array
    {
        return [
            SqlPlayerRepository::class => autowire()->constructor(get(Connection::class)),
            PlayerRepository::class => autowire(SqlPlayerRepository::class)
        ];
    }

    public static function commands(): array
    {
        return [
            MinePlayersNameCommand::class => autowire()
        ];
    }

    public static function useCases(): array
    {
        return [
            AddPlayer::class => autowire(),
            FindAllPlayers::class => autowire()->constructor(
                get(PlayerRepository::class),
                get('Cache.Players')
            ),
            MarkAsMined::class => autowire(),
            PlayerUseCases::class => autowire(),
        ];
    }

    public static function controllers(): array
    {
        return [
            PlayerController::class => autowire()
        ];
    }
}
