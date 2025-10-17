<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;

trait FilterableByRarities
{
    /**
     * @var ItemRarity[] $rarities
     */
    public array $rarities = [];

    /**
     * @param ItemRarity[] $rarities
     */
    public function setRarities(array $rarities)
    {
        $this->rarities = $rarities;
    }
}
