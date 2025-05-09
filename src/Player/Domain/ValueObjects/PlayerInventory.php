<?php

declare(strict_types=1);

namespace AqWiki\Player\Domain\ValueObjects;

use AqWiki\Shared\Domain\Enums\{ResultStatus, TagType};
use AqWiki\Shared\Domain\Abstractions\Inventory;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Items\Domain\Abstractions\AqwItem;

class PlayerInventory extends Inventory
{
    /** @return Result<null> */
    public function add(AqwItem $item)
    {
        if (($this->getAvaliableSpaces() - 1) < 0) {
            return new Result(ResultStatus::Error, 'There is no space avaliable in the player inventory.', null);
        }

        if ($this->has($item)) {
            return new Result(ResultStatus::Error, 'The player inventory already has that item.', null);
        }

        $this->items[md5($item->getGuid())] = $item;

        return new Result(ResultStatus::Success, null, null);
    }

    /** @return Result<null> */
    public function delete(AqwItem $item)
    {
        if ($item->getTags()->has(TagType::AdventureCoins)) {
            return new Result(ResultStatus::Error, 'You cant delete that item, AC tag present.', null);
        }

        unset($this->items[md5($item->getGuid())]);
        return new Result(ResultStatus::Success, null, null);
    }

    public function getAvaliableSpaces(): int
    {
        return $this->maxSpaces - $this->count();
    }
}
