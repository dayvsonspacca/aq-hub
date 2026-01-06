<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters;

use OpenApi\Attributes as OA;

class CanAccessBankParameter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'can_access_bank',
            name: 'can_access_bank',
            in: 'query',
            description: 'The item can access bank (Y or N).',
            example: 'Y'
        );
    }
}
