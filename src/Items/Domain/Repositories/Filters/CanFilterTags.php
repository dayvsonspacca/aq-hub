<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Enums\TagType;

trait CanFilterTags
{
    /**
     * @var TagType[] $tags
     */
    public array $tags = [];

    /**
     * @param TagType[] $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }
}