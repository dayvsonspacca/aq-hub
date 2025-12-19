<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Items\Application\{Armors, Capes};
use AqHub\Items\Domain\Repositories\{ArmorRepository, CapeRepository};
use AqHub\Items\Infrastructure\Http\Controllers\Rest\{ArmorController, CapeController};
use AqHub\Items\Infrastructure\Repositories\Pgsql\{PgsqlArmorRepository, PgsqlCapeRepository};
use AqHub\Shared\Infrastructure\Cache\FileCacheFactory;

use function DI\{add, autowire, factory, get};

class ItemsDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return array_merge(
            self::repositories(),
            self::queries(),
            self::controllers()
        );
    }

    private static function repositories(): array
    {
        return [
            PgsqlArmorRepository::class => autowire()->constructor(get(PgsqlConnection::class), get('QueryBuilder.Pgsql')),
            PgsqlCapeRepository::class => autowire()->constructor(get(PgsqlConnection::class), get('QueryBuilder.Pgsql')),

            ArmorRepository::class => get(PgsqlArmorRepository::class),
            CapeRepository::class => get(PgsqlCapeRepository::class)
        ];
    }

    private static function controllers(): array
    {
        return [
            ArmorController::class => autowire(),
            CapeController::class => autowire(),
            'Controllers.Rest' => add([
                get(ArmorController::class),
                get(CapeController::class)
            ])
        ];
    }

    private static function queries(): array
    {
        return [
            Armors\Queries\FindAll::class => autowire()->constructor(
                get(ArmorRepository::class),
                factory([FileCacheFactory::class, 'armors'])
                    ->parameter('cachePath', get('Path.Cache'))
            ),
            Capes\Queries\FindAll::class => autowire()->constructor(
                get(CapeRepository::class),
                factory([FileCacheFactory::class, 'capes'])
                    ->parameter('cachePath', get('Path.Cache'))
            ),
        ];
    }
}
