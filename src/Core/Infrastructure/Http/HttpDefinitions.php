<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use AqHub\Core\Interfaces\DefinitionsInterface;
use DI\Container;

use function DI\autowire;
use function DI\get;

final class HttpDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [
            'Controllers.Rest' => [],
            HttpHandler::class => autowire()->constructor(get(Container::class), get('Controllers.Rest'))
        ];
    }
}