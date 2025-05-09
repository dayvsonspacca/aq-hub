<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\{ResultStatus, TagType};
use AqWiki\Shared\Domain\ValueObjects\Result;
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

    /** @return Result<self> */
    public function add(TagType $tag)
    {
        if ($this->has($tag)) {
            return new Result(ResultStatus::Error, 'That item already have this tag.', null);
        }

        $this->tags[] = $tag;
        return new Result(ResultStatus::Success, null, $this);
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
}
