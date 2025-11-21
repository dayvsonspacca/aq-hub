<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters;

use OpenApi\Attributes as OA;

class PageSizeParameter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'page_size',
            name: 'page_size',
            in: 'query',
            description: 'The number of results per page (max: 100).',
            example: 1
        );
    }
}
