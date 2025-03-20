<?php

declare(strict_types=1);

namespace AqWiki\Domain\ValueObjects;

use AqWiki\Domain\{Enums, Exceptions};

final class ItemTags implements \Countable
{
    /** @var Enums\TagType[] $tags */
    private array $tags = [];


    /** @param Enums\TagType[] $tags */
    public function __construct(array $tags)
    {
        $this->tags = $tags;
    }

    public function add(Enums\TagType $tag): self
    {
        if ($this->has($tag)) {
            throw new Exceptions\DuplicateItemTagException('The tag `'. $tag->name .'` is already in the item tags.');
        }
        $this->tags[] = $tag;
        return $this;
    }

    public function has(Enums\TagType $tag)
    {
        return in_array($tag, $this->tags);
    }

    public function count(): int
    {
        return count($this->tags);
    }
}
