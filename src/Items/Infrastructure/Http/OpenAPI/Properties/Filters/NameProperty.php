<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

trait NameProperty
{
    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'The requested item name.',
        example: 'ArchPaladin Armor',
        nullable: true
    )]
    public string $name;
}
