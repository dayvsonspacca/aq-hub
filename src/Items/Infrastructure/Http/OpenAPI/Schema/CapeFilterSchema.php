<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\{NameProperty, RaritiesProperty, TagsProperty};
use AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters\{PageProperty, PageSizeProperty};
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CapeFilterSchema',
    title: 'Cape Filters Details',
    description: 'The cape requested filter.',
    properties: [
        new NameProperty(),
        new PageProperty(),
        new PageSizeProperty(),
        new RaritiesProperty(),
        new TagsProperty(),
        new OA\Property(
            property: 'can_access_bank',
            type: 'boolean',
            description: 'The requested access to bank.',
            nullable: true
        )
    ]
)]
class CapeFilterSchema
{
}
