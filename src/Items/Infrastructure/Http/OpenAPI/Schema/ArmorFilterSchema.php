<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\{NameProperty, RaritiesProperty, TagsProperty};
use AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters\{PageProperty, PageSizeProperty};
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorFilterSchema',
    title: 'Armor Filters Details',
    description: 'The armor requested filter.',
    properties: [
        new NameProperty(),
        new PageProperty(),
        new PageSizeProperty(),
        new RaritiesProperty(),
        new TagsProperty()
    ]
)]
class ArmorFilterSchema
{
}
