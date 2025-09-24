<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Enums\WeaponType;

interface WeaponRepository extends AqwItemRepository
{
    /**
     * @return Result<StringIdentifier|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result;
}
