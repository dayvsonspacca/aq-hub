<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

trait TagsProperty
{
    #[OA\Property(
        property: 'tags',
        type: 'array',
        description: 'The requested items tags.',
        items: new OA\Items(type: 'string'),
        example: ['Seasonal', 'ac'],
        nullable: true
    )]
    public ?array $tags;
}
