<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\CanPaginate;

class ArmorFilter
{
    use CanFilterTags;
    use CanFilterRarities;
    use CanPaginate;

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
