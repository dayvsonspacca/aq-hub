<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\OpenAPI\QueryParameters;

use AqHub\Items\Domain\Enums\ItemRarity;
use OpenApi\Attributes as OA;

class RaritiesParameter extends OA\Parameter
{
    public function __construct()
    {
        return parent::__construct(
            parameter: 'rarities',
            name: 'rarities',
            in: 'query',
            description: 'A list of items rarities separeted by comma.',
            example: join(',', array_map(fn($rarity) => $rarity->toString(), ItemRarity::cases()))
        );
    }
}
