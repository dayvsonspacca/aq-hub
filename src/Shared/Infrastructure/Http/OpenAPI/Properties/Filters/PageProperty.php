<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

trait PageProperty
{
    #[OA\Property(
        property: 'page',
        type: 'integer',
        description: 'The requested page number.',
        example: 1,
        minimum: 1
    )]
    public string $page;
}
