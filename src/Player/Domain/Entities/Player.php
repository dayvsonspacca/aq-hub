<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Entities;

use AqHub\Player\Domain\ValueObjects\{PlayerInventory, Name};
use AqHub\Shared\Domain\ValueObjects\{Result, Identifier};
use AqHub\Shared\Domain\Abstractions\Entity;

class Player extends Entity
{
    private function __construct(
        Identifier $id,
        private readonly Name $name,
        private readonly int $level,
        private PlayerInventory $inventory
    ) {
        $this->id = $id;
    }

    /** @return Result<Player> */
    public static function create(
        Identifier $id,
        Name $name,
        int $level,
        PlayerInventory $inventory
    ) {
        if ($level < 0) {
            return Result::error('The level of a player cant be negative.', null);
        }

        return Result::success(null, new self($id, $name, $level, $inventory));
    }

    public function getName(): string
    {
        return $this->name->value;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getInventory(): PlayerInventory
    {
        return $this->inventory;
    }
}
