<?php

declare(strict_types=1);

namespace AqHub\Core\Infrastructure\Http;

use AqHub\Core\Interfaces\DefinitionsInterface;

use function DI\{add, autowire, get};

use DI\Container;

class HttpDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [
            ApiAuthController::class => autowire(),
            'Controllers.Rest' => add([
                get(ApiAuthController::class)
            ]),
            HttpHandler::class => autowire()->constructor(get(Container::class), get('Controllers.Rest')),

            JwtAuthService::class => autowire(),
            JwtAuthMiddleware::class => autowire()
        ];
    }
}
