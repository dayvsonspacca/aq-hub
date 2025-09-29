<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Repositories\Data\HelmetData;
use AqHub\Shared\Domain\ValueObjects\Result;

interface HelmetRepository extends AqwItemRepository
{
    /**
     * @return Result<HelmetData|null>
     */
    public function persist(ItemInfo $itemInfo): Result;
}
