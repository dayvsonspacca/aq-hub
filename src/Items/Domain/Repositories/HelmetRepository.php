<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Repositories\Data\HelmetData;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Core\Result;

interface HelmetRepository
{
    /**
     * @return Result<HelmetData|null>
     */
    public function persist(ItemInfo $itemInfo): Result;
}
