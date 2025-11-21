<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class ListArmorsResponse extends OA\Response
{
    public function __construct()
    {
        return parent::__construct(
            response: Response::HTTP_OK,
            description: 'List of armors',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'filter',
                        description: 'Filter used.',
                        ref: '#/components/schemas/ArmorFilterSchema',
                    ),
                    new OA\Property(
                        property: 'armors',
                        type: 'array',
                        description: 'The list of armors.',
                        items: new OA\Items(ref: '#/components/schemas/ArmorSchema')
                    )
                ]
            )
        );
    }
}
