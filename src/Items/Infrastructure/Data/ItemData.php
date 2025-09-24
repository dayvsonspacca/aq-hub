<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Data;

use AqHub\Items\Domain\ValueObjects\{Name, Description};

class ItemData
{
    public function __construct(
        public readonly Name $name,
        public readonly Description $description
    ) {}
}
