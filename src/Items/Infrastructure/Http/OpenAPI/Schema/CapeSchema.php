<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Schema;

use AqHub\Items\Infrastructure\Http\OpenAPI\Properties\Schemas\CommonItemProperties;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CapeSchema',
    title: 'Cape Details',
    description: 'The complete structure of a cape item returned by the API.'
)]
class CapeSchema
{
    use CommonItemProperties;
}
