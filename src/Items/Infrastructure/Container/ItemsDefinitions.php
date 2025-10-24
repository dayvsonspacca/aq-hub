<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Items\Application\Armors;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Infrastructure\Http\Controllers\Rest\ArmorController;
use AqHub\Items\Infrastructure\Repositories\Pgsql\PgsqlArmorRepository;
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
            ArmorRepository::class => get(PgsqlArmorRepository::class)
        ];
    }

    private static function controllers(): array
    {
        return [
            ArmorController::class => autowire(),
            'Controllers.Rest' => add([
                get(ArmorController::class)
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
            )
        ];
    }
}
