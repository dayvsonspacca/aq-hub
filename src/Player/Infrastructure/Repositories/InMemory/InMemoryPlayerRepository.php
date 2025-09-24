<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\InMemory;

use AqHub\Player\Domain\ValueObjects\{Name, Level, PlayerInventory};
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\Entities\Player;

class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var array<Player> $memory description */
    private array $memory = [];

    /**
     * @return Result<Player|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result
    {
        if ($this->findByIdentifier($identifier)->isSuccess()) {
            return Result::error('A player with same id already exists: ' . $identifier->getValue(), null);
        }

        $player = Player::create($identifier, $name, $level, new PlayerInventory([], 30));

        $this->memory[$identifier->getValue()] = $player->getData();

        return Result::success(null, $player->getData());
    }

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }

    public function findAll(): Result
    {
        return Result::success(null, $this->memory);
    }
}
