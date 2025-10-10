<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\CanPaginate;

class CapeFilter
{
    use CanFilterTags;
    use CanFilterRarities;
    use CanPaginate;
    use CanFilterName;

    public ?bool $canAccessBank = null;

    public function setCanAccessBank(bool $can)
    {
        $this->canAccessBank = $can;
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'page_size' => $this->pageSize,
            'rarities' => array_map(fn ($rarity) => $rarity->toString(), $this->rarities),
            'name' => isset($this->name) && !is_null($this->name) ? $this->name->value : null,
            'can_access_bank' => isset($this->canAccessBank) ? $this->canAccessBank : null,
            'tags' => array_map(fn ($tag) => $tag->toString(), $this->tags),
        ];
    }

    public function generateUniqueKey(): string
    {
        $key = 'page-' . $this->page;

        if (!empty($this->rarities)) {
            $rarities = array_map(fn ($rarity) => $rarity->toString(), $this->rarities);
            $key .= '_rarities-' . implode(',', $rarities);
        }

        if (!empty($this->tags)) {
            $tags = array_map(fn ($rarity) => $rarity->toString(), $this->tags);
            $key .= '_tags-' . implode(',', $tags);
        }

        if (isset($this->name) && !is_null($this->name)) {
            $key .= '_name-' . $this->name->value;
        }

        if (!is_null($this->canAccessBank)) {
            $key .= 'can_access_bank-' . $this->canAccessBank;
        }

        return md5($key);
    }
}
