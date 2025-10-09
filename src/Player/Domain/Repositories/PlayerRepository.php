<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories;

use AqHub\Player\Domain\Repositories\Data\PlayerData;
use AqHub\Player\Domain\Repositories\Filters\PlayerFilter;
use AqHub\Player\Domain\ValueObjects\{Level, Name};
use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};

interface PlayerRepository
{
    /**
     * @return Result<PlayerData|null>
     */
    public function persist(IntIdentifier $identifier, Name $name, Level $level): Result;

    /**
     * @return Result<PlayerData|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result;

    /**
     * @return Result<array<PlayerData>>
     */
    public function findAll(PlayerFilter $filter): Result;

    public function markAsMined(Name $name): Result;
}
