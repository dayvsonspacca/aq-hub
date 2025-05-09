<?php

declare(strict_types=1);

namespace AqWiki\Player\Domain\Entities;

use AqWiki\Shared\Domain\Enums\{ResultStatus, TagType};
use AqWiki\Shared\Domain\Abstractions\Inventory;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Items\Domain\Abstractions\AqwItem;

class PlayerInventory extends Inventory
{
    public function add(AqwItem $item)
    {
        if (($this->avaliableSpaces - 1) < 0) {
            return new Result(ResultStatus::Error, 'There is no space avaliable in the player inventory.', null);
        }

        if ($this->has($item)) {
            return new Result(ResultStatus::Error, 'The player inventory alread has that item.', null);
        }

        $this->items[$item->guid] = $item;
        $this->avaliableSpaces -= 1;

        return new Result(ResultStatus::Success, null, $this);
    }

    public function delete(AqwItem $item)
    {
        if ($item->getTags()->has(TagType::AdventureCoins)) {
            return new Result(ResultStatus::Error, 'You cant delete that item, AC tag present.', null);
        }

        unset($this->items[$item->guid]);
        $this->avaliableSpaces += 1;

        return new Result(ResultStatus::Success, null, $this);
    }
}
