<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\HandlesPagination;

trait HasDefaultFilters
{
    use FilterableByTags;
    use FilterableByRarities;
    use HandlesPagination;
    use FilterableByName;

    protected function defaultsArray(): array
    {
        return [
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $this->rarities),
            'tags' => array_map(fn ($tag) => $tag->toString(), $this->tags),
            'name' => isset($this->name) && !is_null($this->name) ? $this->name->value : null
        ];
    }

    protected function defaultsUniqueKey(): string
    {
        $data = [
            'page' => $this->page,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $this->rarities),
            'tags' => array_map(fn ($tag) => $tag->toString(), $this->tags),
            'name' => isset($this->name) ? $this->name->value : null,
        ];

        return md5(json_encode($data));
    }
}
