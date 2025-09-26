<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Infrastructure\Repositories\Data\WeaponData;
use AqHub\Shared\Domain\ValueObjects\Result;

interface WeaponRepository extends AqwItemRepository
{
    /**
     * @return Result<WeaponData|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result;
}
