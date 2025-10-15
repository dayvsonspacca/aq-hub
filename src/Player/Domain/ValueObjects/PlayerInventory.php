<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\ValueObjects;

use AqHub\Items\Domain\Abstractions\AqwItem;
use AqHub\Shared\Domain\Abstractions\Inventory;
use AqHub\Shared\Domain\Enums\ItemTag;
use AqHub\Core\Result;

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

        $this->items[md5($item->getName())] = $item;

        return Result::success(null, null);
    }

    /** @return Result<null> */
    public function delete(AqwItem $item)
    {
        if ($item->getTags()->has(ItemTag::AdventureCoins)) {
            return Result::error('You cant delete that item, AC tag present.', null);
        }

        unset($this->items[md5($item->getName())]);
        return Result::success(null, null);
    }

    public function getAvaliableSpaces(): int
    {
        return $this->maxSpaces - $this->count();
    }
}
