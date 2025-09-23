<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\Result;

class ItemInfo
{
    private function __construct(
        private readonly Name $name,
        private readonly string $description,
        private ItemTags $tags
    ) {
    }

    /** @return Result<ItemInfo> **/
    public static function create(Name $name, string $description, ItemTags $tags)
    {
        $description = trim($description);

        if (empty($description)) {
            return Result::error('The description of an item cant be empty.', null);
        }

        return Result::success(null, new self($name, $description, $tags));
    }

    public function getName(): string
    {
        return $this->name->value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTags(): ItemTags
    {
        return $this->tags;
    }
}
