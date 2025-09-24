<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Items\Infrastructure\Commands\{AddItemCommand, MineCharpageItemsCommand};
use AqHub\Items\Infrastructure\Repositories\Sql\SqliteWeaponRepository;
use AqHub\Shared\Infrastructure\Container\ContainerRegistry;
use AqHub\Items\Application\UseCases\Weapon\AddWeapon;
use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Shared\Infrastructure\Database\Connection;

use function DI\autowire;
use function DI\get;

class ItemsContainerRegistry implements ContainerRegistry
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
            SqliteWeaponRepository::class => autowire()->constructor(get(Connection::class)),
            WeaponRepository::class       => autowire(SqliteWeaponRepository::class)
        ];
    }

    public static function registerCommands(): array
    {
        return [
            AddItemCommand::class           => autowire(),
            MineCharpageItemsCommand::class => autowire()
        ];
    }

    public static function registerUseCases(): array
    {
        return [
            AddWeapon::class => autowire()
        ];
    }
}
