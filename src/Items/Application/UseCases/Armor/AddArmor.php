<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Armor;

use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\Contracts\Cache;
use AqHub\Core\Result;

class AddArmor
{
    public function __construct(
        private readonly ArmorRepository $armorRepository,
        private readonly Cache $cache
    ) {
    }

    public function execute(ItemInfo $itemInfo): Result
    {
        $this->cache->invalidateTags(['invalidate-on-new-armor']);

        return $this->armorRepository->persist($itemInfo);
    }
}
