<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorFilterSchema',
    title: 'Armor List Filters',
    description: 'Available filters and pagination parameters for listing Armors.'
)]
class ArmorFilterSchema
{
    #[OA\Property(
        property: 'page',
        type: 'integer',
        description: 'The requested page number for pagination.',
        example: 1,
        minimum: 1
    )]
    public int $page;

    #[OA\Property(
        property: 'page_size',
        type: 'integer',
        description: 'The number of results per page.',
        example: 20,
        minimum: 1,
        maximum: 100
    )]
    public int $pageSize;

    #[OA\Property(
        property: 'rarities',
        type: 'array',
        description: 'A list of armor rarity types to filter by (e.g., Rare, Epic).',
        items: new OA\Items(type: 'string'),
        example: ['Rare', 'Legendary'],
        nullable: true
    )]
    public ?array $rarities;

    #[OA\Property(
        property: 'tags',
        type: 'array',
        description: 'A list of tags associated with the armors to filter by.',
        items: new OA\Items(type: 'string'),
        example: ['Seasonal', 'ac'],
        nullable: true
    )]
    public ?array $tags;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'A partial or full name to filter the armors (e.g., name_contains).',
        example: 'ArchPaladin',
        nullable: true
    )]
    public ?string $name;
}
