<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Forms;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Shared\Domain\ValueObjects\Result;
use Symfony\Component\HttpFoundation\Request;

class ArmorFilterForm
{
    /**
     * @return Result<ArmorFilter>
     */
    public static function fromRequest(Request $request): Result
    {
        $page = (int) $request->get('page', 1);

        if ($page <= 0) {
            return Result::error('Param page cannot be zero or negative.', null);
        }

        $filter = new ArmorFilter(
            page: $page
        );

        $rarities = $request->get('rarities', false);
        if ($rarities) {
            $rarities = explode(',', $rarities);

            sort($rarities);

            $rarities = array_map(
                fn($rawRarity) => ItemRarity::fromString($rawRarity)->getData(),
                array_filter($rarities, fn($rawRarity) => ItemRarity::fromString($rawRarity)->isSuccess())
            );

            $filter->rarities = $rarities;
        }

        return Result::success(null, $filter);
    }
}
