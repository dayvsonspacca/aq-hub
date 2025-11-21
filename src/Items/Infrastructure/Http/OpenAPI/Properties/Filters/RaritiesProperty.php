<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

class RaritiesProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'rarities',
            type: 'array',
            description: 'The requested items rarities.',
            items: new OA\Items(type: 'string'),
            example: ['Rare', 'Legendary'],
            nullable: true
        );
    }
}
