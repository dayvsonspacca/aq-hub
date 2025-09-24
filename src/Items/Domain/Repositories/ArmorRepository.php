<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Items\Domain\Entities\Armor;

interface ArmorRepository extends AqwItemRepository
{
    /**
     * @return Result<Armor|null>
     */
    public function persist(ItemInfo $itemInfo): Result;
}
