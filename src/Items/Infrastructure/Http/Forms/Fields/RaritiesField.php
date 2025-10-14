<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\Fields;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Shared\Infrastructure\Http\Forms\EnumListProcessor;
use Symfony\Component\HttpFoundation\Request;

class RaritiesField
{
    /**
     * @return ItemRarity[]
     */
    public static function fromRequest(Request $request): array
    {
        $rarities = $request->get('rarities', '');
        $rarities = EnumListProcessor::fromComma($rarities, ItemRarity::class);

        return $rarities;
    }
}
