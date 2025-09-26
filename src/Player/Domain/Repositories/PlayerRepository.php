<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories;

use AqHub\Player\Domain\Entities\Player;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Player\Infrastructure\Data\PlayerData;
use AqHub\Player\Infrastructure\Repositories\Filters\PlayerFilter;
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};

interface PlayerRepository
{
    /**
     * @return Result<Player|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result;

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result;

    /**
     * @return Result<array<PlayerData>>
     */
    public function findAll(PlayerFilter $filter): Result;

    public function markAsMined(Name $name);
}
