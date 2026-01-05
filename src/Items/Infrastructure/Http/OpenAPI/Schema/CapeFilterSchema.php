<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\CanAccessBankProperty;
use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\CommonItemFilters;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CapeFilterSchema',
    title: 'Cape Filters Details',
    description: 'The cape requested filter.',
    properties: [
        new CanAccessBankProperty()
    ]
)]
class CapeFilterSchema
{
    use CommonItemFilters;
}
