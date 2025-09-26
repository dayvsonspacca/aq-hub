<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};
use DateTime;

class CapeData
{
    public function __construct(
        public readonly Name $name,
        public readonly Description $description,
        public readonly ItemTags $tags,
        public readonly DateTime $registeredAt
    ) {
    }
}
