<?php

declare(strict_types=1);

namespace AqWiki\Player\Domain\ValueObjects;

use AqWiki\Shared\Domain\Abstractions\Inventory;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Items\Domain\Abstractions\AqwItem;
use AqWiki\Shared\Domain\Enums\TagType;

class PlayerInventory extends Inventory
{
    /** @return Result<null> */
    public function add(AqwItem $item)
    {
        if (($this->getAvaliableSpaces() - 1) < 0) {
            return Result::error('There is no space avaliable in the player inventory.', null);
        }

        if ($this->has($item)) {
            return Result::error('The player inventory already has that item.', null);
        }

        $this->items[md5($item->getGuid())] = $item;

        return Result::success(null, null);
    }

    /** @return Result<null> */
    public function delete(AqwItem $item)
    {
        if ($item->getTags()->has(TagType::AdventureCoins)) {
            return Result::error('You cant delete that item, AC tag present.', null);
        }

        unset($this->items[md5($item->getGuid())]);
        return Result::success(null, null);
    }

    public function getAvaliableSpaces(): int
    {
        return $this->maxSpaces - $this->count();
    }
}
