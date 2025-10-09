<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\InMemory;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\Repositories\PlayerRepository;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use DateTime;

class InMemoryPlayerRepository implements PlayerRepository
{
    /** @var array<PlayerData> */
    private array $memory = [];

    /** @var array<string, DateTime> */
    private array $playersMined = [];

    /**
     * @return Result<PlayerData|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result
    {

        $existing = $this->findByIdentifier($identifier);
        if ($existing->isSuccess() && $existing->getData() !== null) {
            return Result::error('A player with same id already exists: ' . $identifier->getValue(), null);
        }

        $playerData = new PlayerData(
            $identifier,
            $name,
            $level,
            new DateTime(),
            false
        );

        $this->memory[$identifier->getValue()] = $playerData;

        return Result::success(null, $playerData);
    }

    /**
     * @return Result<PlayerData|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result
    {
        if (!isset($this->memory[$identifier->getValue()])) {
            return Result::error(null, null);
        }

        return Result::success(null, $this->memory[$identifier->getValue()]);
    }

    /**
     * @return Result<array<PlayerData>>
     */
    public function findAll(PlayerFilter $filter): Result
    {
        $players = array_map(
            fn (PlayerData $player) => $player,
            $this->memory
        );

        if (!is_null($filter->mined)) {
            $players = array_values(array_filter(
                $players,
                fn (PlayerData $player) => $player->mined === $filter->mined
            ));
        }

        return Result::success(null, $players);
    }

    /**
     * @return Result<null>
     */
    public function markAsMined(Name $name): Result
    {
        if (isset($this->playersMined[$name->value])) {
            return Result::error('The player ' . $name->value . ' is already mined.', null);
        }

        $this->playersMined[$name->value] = new DateTime();

        foreach ($this->memory as $id => $playerData) {
            if ($playerData->name->value === $name->value) {
                $this->memory[$id] = new PlayerData(
                    $playerData->identifier,
                    $playerData->name,
                    $playerData->level,
                    $playerData->registeredAt,
                    true
                );
            }
        }

        return Result::success(null, null);
    }
}
