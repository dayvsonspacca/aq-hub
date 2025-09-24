<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\InMemory;

use AqHub\Shared\Domain\ValueObjects\{Identifier, Result};
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\PlayerInventory;
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Domain\Entities\Player;

class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var array<Player> $memory description */
    private array $memory = [];

    /**
     * @return Result<Identifier|null>
     */
    public function persist(Identifier $identifier, Name $name): Result
    {
        if ($this->findByIdentifier($identifier)->isSuccess()) {
            return Result::error('A player with same id already exists: ' . $identifier->getValue(), null);
        }

        $player = Player::create($identifier, $name, 1, new PlayerInventory([], 30));

        $this->memory[$identifier->getValue()] = $player->getData();

        return Result::success(null, $identifier);
    }

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(Identifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }
}
