<?php

declare(strict_types=1);

namespace AqHub\Player\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{Identifier, Result};
use AqHub\Player\Domain\Entities\Player;

interface PlayerRepository
{
    /**
     * @return Result<Identifier|null>
     */
    public function persist(Identifier $identifier): Result;

    /**
     * @return Result<Player|null>
     */
    public function findByIdentifier(Identifier $identifier): Result;
}
