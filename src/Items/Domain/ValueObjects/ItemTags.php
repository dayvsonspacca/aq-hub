<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\ValueObjects;

use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Shared\Domain\Enums\TagType;
use IteratorAggregate;
use ArrayIterator;
use Countable;

class ItemTags implements Countable, IteratorAggregate
{
    /** @var TagType[] $tags */
    private array $tags = [];


    /** @param TagType[] $tags */
    public function __construct(array $tags = [])
    {
        $this->tags = $tags;
    }

    /** @return Result<null> */
    public function add(TagType $tag)
    {
        if ($this->has($tag)) {
            return Result::error('That item already have this tag.', null);
        }

        $this->tags[] = $tag;
        return Result::success(null, null);
    }

    public function has(TagType $tag)
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

    public function toArray(): array
    {
        return array_map(fn (TagType $tag) => $tag->toString(), $this->tags);
    }
}
