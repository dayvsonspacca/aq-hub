<?php

declare(strict_types=1);

namespace AqHub\Items\Application\UseCases\Weapon;

use AqHub\Items\Domain\Repositories\WeaponRepository;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\Result;
use AqHub\Items\Domain\Enums\WeaponType;

class AddWeapon
{
    public function __construct(private readonly WeaponRepository $weaponRepository)
    {
    }

    public function execute(ItemInfo $itemInfo, WeaponType $type): Result
    {
        return $this->weaponRepository->persist($itemInfo, $type);
    }
}
