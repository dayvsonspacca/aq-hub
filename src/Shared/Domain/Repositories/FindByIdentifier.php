<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Repositories;

use AqHub\Shared\Domain\Abstractions\Identifier;
use AqHub\Shared\Domain\Abstractions\Data;

interface FindByIdentifier
{
    public function findByIdentifier(Identifier $identifier): Data;
}