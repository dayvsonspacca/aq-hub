<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters;

use AqHub\Shared\Domain\Enums\ItemTag;
use OpenApi\Attributes as OA;

class TagsParamenter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'tags',
            name: 'tags',
            in: 'query',
            description: 'A list of tags associated to the item separed by comma',
            example: join(',', array_map(fn ($tag) => $tag->toString(), ItemTag::cases()))
        );
    }
}
