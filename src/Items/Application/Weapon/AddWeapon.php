<?php

declare(strict_types=1);

namespace AqWiki\Items\Application\Weapon;

use AqWiki\Items\Domain\Repositories\WeaponRepository;
use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Items\Domain\Enums\WeaponType;

class AddWeapon
{
    public function __construct(private readonly WeaponRepository $weaponRepository) {}

    public function execute(ItemInfo $itemInfo, WeaponType $type): Result
    {
        return $this->weaponRepository->persist($itemInfo, $type);
    }
}
