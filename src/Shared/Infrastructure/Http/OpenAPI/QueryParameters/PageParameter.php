<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI\QueryParameters;

use OpenApi\Attributes as OA;

class PageParameter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'page',
            name: 'page',
            in: 'query',
            description: 'The requested page number for pagination.',
            example: 1
        );
    }
}
