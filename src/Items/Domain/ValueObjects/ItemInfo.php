<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\ValueObjects;

use AqHub\Items\Domain\Enums\ItemRarity;
use AqHub\Shared\Domain\ValueObjects\Result;

class ItemInfo
{
    private function __construct(
        private readonly Name $name,
        private readonly Description $description,
        private ItemTags $tags,
        private readonly ?ItemRarity $rarity
    ) {}

    /** @return Result<ItemInfo> **/
    public static function create(Name $name, Description $description, ItemTags $tags, ?ItemRarity $rarity)
    {
        return Result::success(null, new self($name, $description, $tags, $rarity));
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

    public function getRarity(): ?ItemRarity
    {
        return $this->rarity;
    }
}
