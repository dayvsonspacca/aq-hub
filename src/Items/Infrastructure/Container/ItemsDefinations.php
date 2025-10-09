<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Items\Application\UseCases\Armor\AddArmor;
use AqHub\Items\Application\UseCases\Cape\AddCape;
use AqHub\Items\Application\UseCases\Helmet\AddHelmet;
use AqHub\Items\Application\UseCases\Weapon\AddWeapon;
use AqHub\Items\Domain\Repositories\{ArmorRepository, CapeRepository, HelmetRepository, WeaponRepository};
use AqHub\Items\Infrastructure\Console\{MineAllPlayersItemsCommand, MineCharpageItemsCommand};
use AqHub\Items\Infrastructure\Repositories\Sql\{SqlArmorRepository, SqlCapeRepository, SqlHelmetRepository, SqlWeaponRepository};
use AqHub\Shared\Infrastructure\Container\Definations;
use AqHub\Shared\Infrastructure\Database\Connection;

use function DI\{autowire, get};

class ItemsDefinations implements Definations
{
    public static function getDefinitions(): array
    {
        return array_merge(
            self::repositories(),
            self::commands(),
            self::useCases()
        );
    }

    private static function repositories(): array
    {
        return [
            SqlWeaponRepository::class => autowire()->constructor(get(Connection::class)),
            SqlArmorRepository::class => autowire()->constructor(get(Connection::class)),
            SqlHelmetRepository::class => autowire()->constructor(get(Connection::class)),
            SqlCapeRepository::class => autowire()->constructor(get(Connection::class)),
            WeaponRepository::class => autowire(SqlWeaponRepository::class),
            ArmorRepository::class => autowire(SqlArmorRepository::class),
            HelmetRepository::class => autowire(SqlHelmetRepository::class),
            CapeRepository::class => autowire(SqlCapeRepository::class)
        ];
    }

    private static function commands(): array
    {
        return [
            MineAllPlayersItemsCommand::class => autowire(),
            MineCharpageItemsCommand::class => autowire()
        ];
    }

    private static function useCases(): array
    {
        return [
            AddWeapon::class => autowire(),
            AddArmor::class => autowire(),
            AddHelmet::class => autowire(),
            AddCape::class => autowire()
        ];
    }
}
