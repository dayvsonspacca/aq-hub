<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Enums\ItemTag;

trait FilterableByTags
{
    /**
     * @var ItemTag[] $tags
     */
    public array $tags = [];

    /**
     * @param ItemTag[] $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }
}
