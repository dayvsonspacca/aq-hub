<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{Abstractions, Exceptions, Enums};

class PlayerInventory extends Abstractions\Inventory
{
    public function add(Abstractions\AqwItem $item)
    {
        if (($this->avaliableSpaces - 1) < 0) {
            throw Exceptions\InventoryException::unavaliableSpace('PlayerInventory');
        }

        if ($this->has($item)) {
            throw Exceptions\InventoryException::duplicateItem();
        }

        $this->items[$item->guid] = $item;
        $this->avaliableSpaces -= 1;
    }

    public function delete(Abstractions\AqwItem $item)
    {
        if ($item->getTags()->has(Enums\TagType::AdventureCoins)) {
            throw Exceptions\InventoryException::cantDelete();
        }

        unset($this->items[$item->guid]);
        $this->avaliableSpaces += 1;
    }
}
