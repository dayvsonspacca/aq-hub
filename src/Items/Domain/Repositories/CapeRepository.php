<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Repositories\Data\CapeData;
use AqHub\Shared\Domain\ValueObjects\Result;

interface CapeRepository extends AqwItemRepository
{
    /**
     * @return Result<CapeData|null>
     */
    public function persist(ItemInfo $itemInfo, bool $canAccessBank): Result;
}
