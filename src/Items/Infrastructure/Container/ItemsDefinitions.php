<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Infrastructure\Http\Controllers\Rest\ArmorController;
use AqHub\Items\Infrastructure\Repositories\Pgsql\PgsqlArmorRepository;

use function DI\{add, autowire, get};

class ItemsDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return array_merge(
            self::repositories(),
            ['Controllers.Rest' => add(self::controllers())]
        );
    }

    private static function repositories(): array
    {
        return [
            ArmorRepository::class => autowire(PgsqlArmorRepository::class),
            PgsqlArmorRepository::class => autowire()->constructor(get(PgsqlConnection::class), get('QueryBuilder.Pgsql'))
        ];
    }

    private static function controllers(): array
    {
        return [
            ArmorController::class => autowire()
        ];
    }
}
