<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\CommonItemFilters;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorFilterSchema',
    title: 'Armor Filters Details',
    description: 'The armor requested filter.'
    )]
class ArmorFilterSchema
{
    use CommonItemFilters;
}
