<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters;

use OpenApi\Attributes as OA;

class NameParameter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'name',
            name: 'name',
            in: 'query',
            description: 'A partial or full name of an item.',
            example: 'ArchPaladin'
        );
    }
}
