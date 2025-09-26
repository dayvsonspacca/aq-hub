<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Repositories\Data;

use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};

class WeaponData
{
    public function __construct(
        public readonly Name $name,
        public readonly Description $description,
        public readonly ItemTags $tags,
        public readonly WeaponType $type
    ) {
    }
}
