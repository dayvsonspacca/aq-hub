<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI\Properties\Filters;

use OpenApi\Attributes as OA;

class PageSizeProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'page_size',
            type: 'integer',
            description: 'The requested results per page.',
            example: 20,
            minimum: 1,
            maximum: 100
        );
    }
}
