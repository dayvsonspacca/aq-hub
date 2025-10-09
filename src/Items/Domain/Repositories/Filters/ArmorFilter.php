<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Shared\Domain\Enums\TagType;

class ArmorFilter
{
    /**
     * @param ItemRarity[] $rarities
     * @param TagType[] $tags
     */
    public function __construct(
        public array $rarities = [],
        public array $tags = [],
        public readonly int $page = 1,
        public readonly int $pageSize = 25
    ) {
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'rarities' => array_map(fn($rarity) => $rarity->toString(), $this->rarities),
            'tags' => array_map(fn($tag) => $tag->toString(), $this->tags),
        ];
    }
}
