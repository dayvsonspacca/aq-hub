<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\Result;

class ItemInfo
{
    private function __construct(
        private readonly Name $name,
        private readonly Description $description,
        private ItemTags $tags
    ) {
    }

    /** @return Result<ItemInfo> **/
    public static function create(Name $name, Description $description, ItemTags $tags)
    {
        return Result::success(null, new self($name, $description, $tags));
    }

    public function getName(): string
    {
        return $this->name->value;
    }

    public function getDescription(): string
    {
        return $this->description->value;
    }

    public function getTags(): ItemTags
    {
        return $this->tags;
    }
}
