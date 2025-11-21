<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\NameProperty;
use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\RaritiesProperty;
use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters\TagsProperty;
use AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters\PageProperty;
use AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters\PageSizeProperty;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorFilterSchema',
    title: 'Armor Filters Details',
    description: 'Available filters for listing Armors.'
)]
class ArmorFilterSchema
{
    use NameProperty;
    use PageProperty;
    use PageSizeProperty;
    use RaritiesProperty;
    use TagsProperty;
}
