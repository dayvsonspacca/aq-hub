<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories\Data;

use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\ValueObjects\{Description, ItemTags, Name};
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;
use DateTime;

class WeaponData
{
    public function __construct(
        public readonly StringIdentifier $identifier,
        public readonly Name $name,
        public readonly Description $description,
        public readonly ItemTags $tags,
        public readonly WeaponType $type,
        public readonly DateTime $registeredAt
    ) {
    }
}
