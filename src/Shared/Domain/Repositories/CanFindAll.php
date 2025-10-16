<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Repositories;

use AqHub\Shared\Domain\Abstractions\Data;
use AqHub\Shared\Domain\Abstractions\Filter;

interface CanFindAll
{
    /**
     * @return Data[]
     */
    public function findAll(Filter $filter): array;
}