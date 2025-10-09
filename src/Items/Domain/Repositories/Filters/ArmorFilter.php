<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;

class ArmorFilter
{
    /**
     * @param ItemRarity[] $rarities
     */
    public function __construct(
        public array $rarities = [],
        public readonly int $page = 1,
        public readonly int $pageSize = 25
    ) {
    }
}
