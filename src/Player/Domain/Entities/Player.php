<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Entities;

use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Shared\Domain\Abstractions\Entity;
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};

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
            'id' => $this->getId(),
            'name' => $this->getName(),
            'level' => $this->getLevel()
        ];
    }
}
