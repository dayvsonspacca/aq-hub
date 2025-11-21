<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

trait RaritiesProperty
{
    #[OA\Property(
        property: 'rarities',
        type: 'array',
        description: 'The requested items rarities.',
        items: new OA\Items(type: 'string'),
        example: ['Rare', 'Legendary'],
        nullable: true
    )]
    public ?array $rarities;
}
