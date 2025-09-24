<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Entities;

use AqHub\Player\Domain\ValueObjects\{Level, PlayerInventory, Name};
use AqHub\Shared\Domain\ValueObjects\{Result, IntIdentifier};
use AqHub\Shared\Domain\Abstractions\Entity;

class Player extends Entity
{
    private function __construct(
        IntIdentifier $id,
        private readonly Name $name,
        private readonly Level $level,
        private PlayerInventory $inventory
    ) {
        $this->id = $id;
    }

    /** @return Result<Player> */
    public static function create(
        IntIdentifier $id,
        Name $name,
        Level $level,
        PlayerInventory $inventory
    ) {
        return Result::success(null, new self($id, $name, $level, $inventory));
    }

    public function getName(): string
    {
        return $this->name->value;
    }

    public function getLevel(): int
    {
        return $this->level->value;
    }

    public function getInventory(): PlayerInventory
    {
        return $this->inventory;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'level' => $this->getLevel()
        ];
    }
}
