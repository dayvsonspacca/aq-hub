<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\CanPaginate;

trait HasDefaultFilters
{
    use CanFilterTags;
    use CanFilterRarities;
    use CanPaginate;
    use CanFilterName;

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
        $key = 'page-' . $this->page;

        if (!empty($this->rarities)) {
            $rarities = array_map(fn ($rarity) => $rarity->toString(), $this->rarities);
            $key .= '_rarities-' . implode(',', $rarities);
        }

        if (!empty($this->tags)) {
            $tags = array_map(fn ($tag) => $tag->toString(), $this->tags);
            $key .= '_tags-' . implode(',', $tags);
        }

        if (isset($this->name) && !is_null($this->name)) {
            $key .= '_name-' . $this->name->value;
        }

        return $key;
    }
}
