<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

class NameProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'name',
            type: 'string',
            description: 'The requested item name.',
            example: 'ArchPaladin Armor',
            nullable: true
        );
    }
}
