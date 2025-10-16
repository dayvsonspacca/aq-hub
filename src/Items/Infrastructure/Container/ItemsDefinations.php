<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Container;

use AqHub\Core\Infrastructure\Database\PgsqlConnection;
use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Infrastructure\Repositories\Pgsql\PgsqlArmorRepository;

use function DI\{autowire, get};

class ItemsDefinations implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return array_merge(
            self::repositories()
        );
    }

    private static function repositories(): array
    {
        return [
            ArmorRepository::class => autowire(PgsqlArmorRepository::class),
            PgsqlArmorRepository::class => autowire()->constructor(get(PgsqlConnection::class), get('QueryBuilder.Pgsql'))
        ];
    }
}
