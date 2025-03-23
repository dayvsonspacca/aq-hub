<?php

declare(strict_types=1);

namespace AqWiki\Domain\Abstractions;

use AqWiki\Domain\{ValueObjects, Enums, Abstractions};

abstract class AqwItem extends Abstractions\Entity
{
    public ValueObjects\ItemTags $tags;

    public function __construct(
        public readonly string $name,
        public readonly ?Enums\ItemRarity $rarity,
        public readonly ?ValueObjects\GameCurrency $price,
        public readonly ValueObjects\GameCurrency $sellback,
        public readonly string $description
    ) {
        $this->tags = new ValueObjects\ItemTags([]);
    }
}
