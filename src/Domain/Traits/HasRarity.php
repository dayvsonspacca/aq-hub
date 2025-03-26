<?php

declare(strict_types=1);

namespace AqWiki\Domain\Traits;

use AqWiki\Domain\Enums\ItemRarity;

trait HasRarity
{
    private ItemRarity $rarity;

    public function getRarity(): string
    {
        return $this->rarity->name;
    }

    public function setRarity(ItemRarity $rarity): self
    {
        $this->rarity = $rarity;
        return $this;
    }
}
