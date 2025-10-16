<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Repositories;

use AqHub\Shared\Domain\Abstractions\Data;
use AqHub\Shared\Domain\Contracts\Identifier;

interface CanFindByIdentifier
{
    /**
     * @return Data|null
     */
    public function findByIdentifier(Identifier $identifier);
}
