<?php

declare(strict_types=1);

namespace AqWiki\Domain\Entities;

use AqWiki\Domain\{Abstractions, Exceptions};

final class Inventory extends Abstractions\Entity implements \Countable
{
    /** @var array<string, Abstractions\AqwItem> $items */
    private array $items = [];
    private int $spaces = 30;

    public function defineSpaces(int $total)
    {
        if ($total < 0) {
            throw Exceptions\InventoryException::negativeSpaces();
        }
        $this->spaces = $total;
    }

    public function addSpaces(int $number)
    {
        $this->spaces += $number;
    }

    public function addItem(Abstractions\AqwItem $item)
    {
        if (($this->count() + 1) > $this->spaces) {
            throw Exceptions\InventoryException::unavaliableSpace();
        }

        if (isset($this->items[md5(serialize($item))])) {
            throw Exceptions\InventoryException::duplicateItem();
        }

        $this->items[md5(serialize($item))] = $item;
    }

    public function avaliableSpaces(): int
    {
        return $this->spaces - $this->count();
    }

    public function count(): int
    {
        return count($this->items);
    }
}
