<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Filters;

class ArmorFilter
{
    public function __construct(
        public int $page = 1,
        public int $pageSize = 25
    ) {
    }
}
