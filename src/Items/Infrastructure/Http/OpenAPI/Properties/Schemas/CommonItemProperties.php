<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Schemas;

use OpenApi\Attributes as OA;

trait CommonItemProperties
{
    #[OA\Property(
        property: 'id',
        type: 'string',
        description: 'The unique identifier of the item.',
        example: '5a3e1b7c-2d9f-4e0a-9c8b-6f7d5c4b3a21'
    )]
    public string $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'The name of the item.',
        example: 'ArchPaladin Armor'
    )]
    public string $name;

    #[OA\Property(
        property: 'description',
        type: 'string',
        description: 'The full description of the item.',
        example: 'A legendary item found in the depths of Lore.'
    )]
    public string $description;

    #[OA\Property(
        property: 'registered_at',
        type: 'string',
        format: 'date-time',
        description: 'Date and time the item was registered in AQHub API (Y-m-d H:i:s).',
        example: '2025-10-24 16:30:00'
    )]
    public string $registeredAt;

    #[RarityProperty]
    public ?string $rarity;

    #[TagsProperty]
    public array $tags;
}