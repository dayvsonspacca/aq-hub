<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Schemas;

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
            description: 'List of tags associated with the item.',
            items: new OA\Items(type: 'string'),
            example: array_map(fn ($tag) => $tag->toString(), ItemTag::cases()),
            nullable: false
        );
    }
}
