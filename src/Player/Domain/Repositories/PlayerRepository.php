<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{IntIdentifier, Result};
use AqHub\Player\Domain\ValueObjects\Name;
use AqHub\Player\Domain\Entities\Player;

interface PlayerRepository
{
    /**
     * @return Result<IntIdentifier|null>
     */
    public function persist(IntIdentifier $identifier, Name $name): Result;

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(IntIdentifier $identifier): Result;
}
