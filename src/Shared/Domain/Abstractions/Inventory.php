<?php

declare(strict_types=1);

namespace AqWiki\Shared\Domain\Abstractions;

use AqWiki\Items\Domain\Abstractions\AqwItem;
use Countable;

abstract class Inventory extends Entity implements Countable
{
    public function __construct(
        /** @var array<string, AqwItem> $items */
        protected array $items = [],
        protected int $maxSpaces,
        protected int $avaliableSpaces
    ) {
    }

    /** @return Result<Inventory> */
    abstract public function add(AqwItem $item);

    /** @return Result<Inventory> */
    abstract public function delete(AqwItem $item);

    public function has(AqwItem $item): bool
    {
        return in_array($item, $this->items, true);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getMaxSpaces(): int
    {
        return $this->maxSpaces;
    }

    public function getAvaliableSpaces(): int
    {
        return $this->avaliableSpaces;
    }

    /** @return array<string, AqwItem> */
    public function getItems(): array
    {
        return $this->items;
    }
}
