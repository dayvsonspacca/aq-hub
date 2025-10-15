<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Repositories\Data\WeaponData;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Core\Result;

interface WeaponRepository
{
    /**
     * @return Result<WeaponData|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result;
}
