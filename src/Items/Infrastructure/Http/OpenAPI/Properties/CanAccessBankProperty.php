<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Properties;

use OpenApi\Attributes as OA;

class CanAccessBankProperty extends OA\Property
{
    public function __construct()
    {
        parent::__construct(
            property: 'can_access_bank',
            type: 'boolean',
            description: 'Item can access bank.',
            nullable: true,
            example: true
        );
    }
}
