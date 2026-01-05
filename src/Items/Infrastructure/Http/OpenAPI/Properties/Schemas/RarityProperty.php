<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Schemas;

use AqHub\Items\Domain\Enums\ItemRarity;
use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_PROPERTY)]
class RarityProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'rarity',
            type: 'string',
            description: 'The rarity of an item.',
            enum: array_map(fn ($rarity) => $rarity->toString(), ItemRarity::cases()),
            example: ItemRarity::Awesome->toString(),
            nullable: true
        );
    }
}
