<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ArmorSchema',
    title: 'Armor Item Details',
    description: 'The complete structure of an armor item returned by the API.'
)]
class ArmorSchema
{
    #[OA\Property(
        property: 'id',
        type: 'string',
        description: 'The unique identifier of the armor.',
        example: '5a3e1b7c-2d9f-4e0a-9c8b-6f7d5c4b3a21'
    )]
    public string $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'The name of the armor.',
        example: 'ArchPaladin Armor'
    )]
    public string $name;

    #[OA\Property(
        property: 'description',
        type: 'string',
        description: 'The full description of the armor.',
        example: 'As a warrior in one of the Paladin Order’s highest ranks, you have access to powers the likes of which Lore has not yet seen. Use your newfound abilities to fight for virtue and justice, because darkness will flee at the very sight of your righteous light!'
    )]
    public string $description;

    #[OA\Property(
        property: 'registered_at',
        type: 'string',
        format: 'date-time',
        description: 'Date and time the armor was registered (Y-m-d H:i:s format).',
        example: '2025-10-24 16:30:00'
    )]
    public string $registeredAt;

    #[OA\Property(
        property: 'rarity',
        type: 'string',
        nullable: true,
        description: 'The rarity of the armor.',
        example: 'Rare'
    )]
    public ?string $rarity;

    #[OA\Property(
        property: 'tags',
        type: 'array',
        description: 'List of tags associated with the armor.',
        items: new OA\Items(type: 'string'),
        example: ['Legendary', 'Seasonal']
    )]
    public array $tags;
}
