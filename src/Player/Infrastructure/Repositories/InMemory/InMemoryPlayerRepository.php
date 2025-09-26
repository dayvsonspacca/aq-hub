<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\InMemory;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\{Level, Name, PlayerInventory};
use AqHub\Player\Infrastructure\Data\PlayerData;
use AqHub\Player\Infrastructure\Repositories\Filters\PlayerFilter;
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use DateTime;

class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var array<Player> */
    private array $memory = [];

    /** @var array<string, DateTime> */
    private array $playersMined = [];

    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result
    {
        if ($this->findByIdentifier($identifier)->isSuccess()) {
            return Result::error('A player with same id already exists: ' . $identifier->getValue(), null);
        }

        $player                                = Player::create($identifier, $name, $level, new PlayerInventory([], 30));
        $this->memory[$identifier->getValue()] = $player->getData();

        return Result::success(null, $player->getData());
    }

    public function findByIdentifier(IntIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }

    public function findAll(PlayerFilter $filter): Result
    {
        $players = array_map(fn ($player) => PlayerData::fromDomain($player, new DateTime(), isset($this->playersMined[$player->getName()])), $this->memory);

        if (!is_null($filter->mined)) {
            $players = array_values(array_filter($players, fn ($player) => $player->mined === $filter->mined));
        }

        return Result::success(null, $players);
    }

    public function markAsMined(Name $name): Result
    {
        if (isset($this->playersMined[$name->value])) {
            return Result::error('The player ' . $name->value . ' is already mined.', null);
        }

        $this->playersMined[$name->value] = new DateTime();

        return Result::success(null, null);
    }
}
