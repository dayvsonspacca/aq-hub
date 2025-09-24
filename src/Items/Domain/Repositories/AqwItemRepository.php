<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};

interface AqwItemRepository
{
    public function findByIdentifier(StringIdentifier $identifier): Result;
}
