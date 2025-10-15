<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Repositories\Data\ArmorData;
use AqHub\Items\Domain\Repositories\Filters\ArmorFilter;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Core\Result;

interface ArmorRepository
{
    /**
     * @return Result<ArmorData|null>
     */
    public function persist(ItemInfo $itemInfo): Result;

    /**
     * @return Result<array<PlayerData>>
     */
    public function findAll(ArmorFilter $filter): Result;
}
