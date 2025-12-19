<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CapeSchema',
    title: 'Cape Details',
    description: 'The complete structure of a cape item returned by the API.'
)]
class CapeSchema
{
    #[OA\Property(
        property: 'id',
        type: 'string',
        description: 'The unique identifier of the cape.',
        example: '0001e74ac568b93f9a0a92d859640a00'
    )]
    public string $id;

    #[OA\Property(
        property: 'name',
        type: 'string',
        description: 'The name of the cape.',
        example: 'Soaring Moonlit Wings of Dage'
    )]
    public string $name;

    #[OA\Property(
        property: 'description',
        type: 'string',
        description: 'The full description of the cape.',
        example: 'Dage the Good spreads his broad wings to shelter the weak and protect the defenseless against the evils of the world of Lore.'
    )]
    public string $description;

    #[OA\Property(
        property: 'registered_at',
        type: 'string',
        format: 'date-time',
        description: 'Date and time the cape was registered (Y-m-d H:i:s format).',
        example: '2025-10-24 16:30:00'
    )]
    public string $registeredAt;

    #[OA\Property(
        property: 'rarity',
        type: 'string',
        nullable: true,
        description: 'The rarity of the cape.',
        example: 'Rare'
    )]
    public ?string $rarity;

    #[OA\Property(
        property: 'tags',
        type: 'array',
        description: 'List of tags associated with the cape.',
        items: new OA\Items(type: 'string'),
        example: ['Legendary', 'Seasonal']
    )]
    public array $tags;
}
