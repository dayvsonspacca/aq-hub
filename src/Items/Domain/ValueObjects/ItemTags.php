<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\ValueObjects;

use AqHub\Core\Result;
use AqHub\Shared\Domain\Enums\ItemTag;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class ItemTags implements Countable, IteratorAggregate
{
    /** @var ItemTag[] $tags */
    private array $tags = [];


    /** @param ItemTag[] $tags */
    public function __construct(array $tags = [])
    {
        $this->tags = $tags;
    }

    /** @return Result<null> */
    public function add(ItemTag $tag)
    {
        if ($this->has($tag)) {
            return Result::error('That item already have this tag.', null);
        }

        $this->tags[] = $tag;
        return Result::success(null, null);
    }

    public function has(ItemTag $tag)
    {
        return in_array($tag, $this->tags, true);
    }

    public function count(): int
    {
        return count($this->tags);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->tags);
    }

    /**
     * @return array<string>
     */
    public function toArray(): array
    {
        return array_map(fn (ItemTag $tag) => $tag->toString(), $this->tags);
    }
}
