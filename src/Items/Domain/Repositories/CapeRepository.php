<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Entities\Cape;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;

interface CapeRepository extends AqwItemRepository
{
    /**
     * @return Result<Cape|null>
     */
    public function persist(ItemInfo $itemInfo): Result;
}
