<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Items\Infrastructure\Repositories\Sql\{SqlWeaponRepository, SqlArmorRepository};
use AqHub\Items\Infrastructure\Commands\{MineAllPlayersItemsCommand, MineCharpageItemsCommand};
use AqHub\Items\Domain\Repositories\{ArmorRepository, WeaponRepository};
use AqHub\Shared\Infrastructure\Container\ContainerRegistry;
use AqHub\Items\Application\UseCases\Weapon\AddWeapon;
use AqHub\Items\Application\UseCases\Armor\AddArmor;
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
            self::registerCommands(),
            self::registerControllers()
        );
    }

    public static function registerRepositories(): array
    {
        return [
            SqlWeaponRepository::class => autowire()->constructor(get(Connection::class)),
            SqlArmorRepository::class => autowire()->constructor(get(Connection::class)),
            WeaponRepository::class => autowire(SqlWeaponRepository::class),
            ArmorRepository::class => autowire(SqlArmorRepository::class)
        ];
    }

    public static function registerCommands(): array
    {
        return [
            MineAllPlayersItemsCommand::class => autowire(),
            MineCharpageItemsCommand::class => autowire()
        ];
    }

    public static function registerUseCases(): array
    {
        return [
            AddWeapon::class => autowire(),
            AddArmor::class => autowire()
        ];
    }

    public static function registerControllers(): array
    {
        return [];
    }
}
