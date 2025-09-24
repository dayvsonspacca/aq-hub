<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Items\Domain\ValueObjects\ItemInfo;

interface ArmorRepository extends AqwItemRepository
{
    /**
     * @return Result<StringIdentifier|null>
     */
    public function persist(ItemInfo $itemInfo): Result;
}
