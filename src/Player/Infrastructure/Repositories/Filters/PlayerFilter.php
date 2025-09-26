<?php

declare(strict_types=1);

namespace AqHub\Player\Infrastructure\Repositories\Filters;

class PlayerFilter
{
    public function __construct(
        public ?bool $mined = null,
        public int $page = 1,
        public int $pageSize = 25
    ) {}
}
