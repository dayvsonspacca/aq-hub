<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Data;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};

class WikiItemData
{
    public function __construct(
        public readonly Name $name,
        public readonly Description $description,
        public readonly ItemTags $tags,
        public readonly ?ItemRarity $rarity
    ) {
    }
}
