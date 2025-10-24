<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI;

use Attribute;
use OpenApi\Attributes as OA;

#[Attribute(Attribute::TARGET_METHOD)]
/**
 * @codeCoverageIgnore
 */
class ListResponse extends OA\Response
{
    public function __construct(
        int $statusCode,
        string $listSchema,
        string $property,
        string $description,
        string $filterSchema
    ) {
        parent::__construct(
            response: $statusCode,
            description: $description,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: 'filter',
                        type: 'object',
                        description: 'The filters that were applied to the search, based on the request parameters.',
                        ref: '#/components/schemas/' . (new \ReflectionClass($filterSchema))->getShortName()
                    ),
                    new OA\Property(
                        property: $property,
                        type: 'array',
                        description: 'Collection of items.',
                        items: new OA\Items(ref: '#/components/schemas/' . (new \ReflectionClass($listSchema))->getShortName())
                    )
                ]
            )
        );
    }
}
