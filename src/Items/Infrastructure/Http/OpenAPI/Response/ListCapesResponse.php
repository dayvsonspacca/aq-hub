<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\Response;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class ListCapesResponse extends OA\Response
{
    public function __construct()
    {
        return parent::__construct(
            response: Response::HTTP_OK,
            description: 'List of capes',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'filter',
                        description: 'Filter used.',
                        ref: '#/components/schemas/CapeFilterSchema',
                    ),
                    new OA\Property(
                        property: 'capes',
                        type: 'array',
                        description: 'The list of capes.',
                        items: new OA\Items(ref: '#/components/schemas/CapeSchema')
                    ),
                    new OA\Property(
                        property: 'total',
                        type: 'integer',
                        description: 'The total of capes by filter ignoring pagination.'
                    )
                ]
            )
        );
    }
}
