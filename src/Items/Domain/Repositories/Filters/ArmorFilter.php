<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

use AqHub\Shared\Domain\Repositories\Filters\CanPaginate;

class ArmorFilter
{
    use DefaultFilters;

    public function toArray(): array
    {
        return $this->defaultsArray();
    }

    public function generateUniqueKey(): string
    {
        return md5($this->defaultsUniqueKey());
    }
}
