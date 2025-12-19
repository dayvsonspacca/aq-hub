<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms\QueryTraits;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Shared\Domain\Helpers\EnumListProcessor;
use Symfony\Component\HttpFoundation\Request;

trait MapsRarities
{
    /**
     * @return ItemRarity[]
     */
    private static function mapRarities(Request $request): array
    {
        $rarities = $request->get('rarities', '');
        $rarities = EnumListProcessor::fromComma($rarities, ItemRarity::class);

        return $rarities;
    }
}
