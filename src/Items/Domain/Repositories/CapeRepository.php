<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Items\Domain\Repositories\Filters\CapeFilter;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Core\Result;

interface CapeRepository
{
    /**
     * @return Result<CapeData|null>
     */
    public function persist(ItemInfo $itemInfo, bool $canAccessBank): Result;

    /**
     * @return Result<array<CapeData>>
     */
    public function findAll(CapeFilter $filter): Result;
}
