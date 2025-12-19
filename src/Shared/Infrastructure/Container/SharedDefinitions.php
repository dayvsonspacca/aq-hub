<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Container;

use AqHub\Core\Interfaces\DefinitionsInterface;
use AqHub\Shared\Infrastructure\Http\Controllers\Rest\ApiAuthController;
use AqHub\Shared\Infrastructure\Http\Middlewares\JwtAuthMiddleware;
use AqHub\Shared\Infrastructure\Http\Services\JwtAuthService;

use function DI\add;
use function DI\autowire;
use function DI\get;

class SharedDefinitions implements DefinitionsInterface
{
    public static function dependencies(): array
    {
        return [
            ApiAuthController::class => autowire(),
            'Controllers.Rest' => add([
                get(ApiAuthController::class)
            ]),
            JwtAuthService::class => autowire(),
            JwtAuthMiddleware::class => autowire()
        ];
    }
}
