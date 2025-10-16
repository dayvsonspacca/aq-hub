<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Database;

use AqHub\Core\Interfaces\DefinitionsInterface;
use Aura\SqlQuery\QueryFactory;
use AqHub\Core\Env;

use function DI\factory;
use function DI\get;

class DatabaseDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [
            PgsqlConnection::class => factory([PgsqlConnection::class, 'instance'])->parameter('env', get(Env::class))->parameter('forceReload', false),
            'QueryBuilder.Pgsql' => new QueryFactory('pgsql')
        ];
    }
}
