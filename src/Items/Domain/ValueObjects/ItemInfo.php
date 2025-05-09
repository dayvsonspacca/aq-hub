<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\ValueObjects;

use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Shared\Domain\Enums\ResultStatus;

class ItemInfo
{
    private function __construct(
        private readonly string $name,
        private readonly string $description,
        private ItemTags $tags
    ) {
    }

    /** @return Result<ItemInfo> **/
    public static function create(string $name, string $description, ItemTags $tags)
    {
        $name = trim($name);
        $description = trim($description);

        if (empty($name)) {
            return new Result(ResultStatus::Error, 'The name of an item cant be empty.', null);
        }
        if (empty($description)) {
            return new Result(ResultStatus::Error, 'The description of an item cant be empty.', null);
        }

        return new Result(ResultStatus::Success, null, new self($name, $description, $tags));
    }

    public function getName(): string
    {
        return $this->name;
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
