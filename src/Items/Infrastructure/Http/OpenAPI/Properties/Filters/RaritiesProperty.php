<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;
use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_PROPERTY)]
class RaritiesProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'rarities',
            type: 'array',
            description: 'The requested items rarities.',
            items: new OA\Items(type: 'string'),
            example: array_map(fn($rarity) => $rarity->toString(), ItemRarity::cases()),
            nullable: true
        );
    }
}
