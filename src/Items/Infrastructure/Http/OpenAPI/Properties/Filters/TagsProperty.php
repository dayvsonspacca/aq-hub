<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Filters;

use AqHub\Shared\Domain\Enums\ItemTag;
use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_PROPERTY)]
class TagsProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'tags',
            type: 'array',
            description: 'The requested items tags.',
            items: new OA\Items(type: 'string'),
            example: array_map(fn ($tag) => $tag->toString(), ItemTag::cases()),
            nullable: true
        );
    }
}
