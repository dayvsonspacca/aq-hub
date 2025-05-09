<?php

declare(strict_types=1);

namespace AqWiki\Player\Domain\Entities;

use AqWiki\Player\Domain\ValueObjects\PlayerInventory;
use AqWiki\Shared\Domain\Abstractions\Entity;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Shared\Domain\Enums\ResultStatus;

class Player extends Entity
{
    private function __construct(
        string $guid,
        private readonly int $level,
        private PlayerInventory $inventory
    ) {
        $this->guid = $guid;
    }

    /** @return Result<Player> */
    public static function create(
        string $guid,
        int $level,
        PlayerInventory $inventory
    ) {
        $guid = trim($guid);
        if (empty($guid)) {
            return new Result(ResultStatus::Error, 'The GUID of a player cant be empty.', null);
        }
        if ($level < 0) {
            return new Result(ResultStatus::Error, 'The level of a player cant be negative.', null);
        }

        return new Result(ResultStatus::Success, null, new self($guid, $level, $inventory));
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
