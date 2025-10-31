<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\OpenAPI;

use OpenApi\Attributes as OA;

#[OA\OpenApi(
    info: new OA\Info(
        title: 'AQHub API',
        version: '1.0.0',
        contact: new OA\Contact(
            name: "Dayvson Spacca",
            email: "spacca.dayvson@gmail.com"
        )
    ),
    tags: [
        new OA\Tag(name: 'Armors')
    ],
    servers: [
        new OA\Server(url: "https://aqhub-api.dayvsonspacca.com")
    ]
)]
class OpenAPIDocumentation
{
}