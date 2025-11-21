<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

class TagsProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'tags',
            type: 'array',
            description: 'The requested items tags.',
            items: new OA\Items(type: 'string'),
            example: ['Seasonal', 'ac'],
            nullable: true
        );
    }
}
