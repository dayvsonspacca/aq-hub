<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Schemas\CommonItemProperties;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorSchema',
    title: 'Armor Details',
    description: 'The complete structure of an armor item.'
)]
class ArmorSchema
{
    use CommonItemProperties;
}
