<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Armor;

use AqHub\Items\Domain\Repositories\ArmorRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;

class AddArmor
{
    public function __construct(private readonly ArmorRepository $armorRepository)
    {
    }

    public function execute(ItemInfo $itemInfo): Result
    {
        return $this->armorRepository->persist($itemInfo);
    }
}
