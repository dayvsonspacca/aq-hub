<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Items\Domain\Enums\WeaponType;

interface WeaponRepository extends AqwItemRepository
{
    /**
     * @return Result<Weapon|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result;
}
