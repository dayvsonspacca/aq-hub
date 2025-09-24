<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Repositories;

use AqHub\Shared\Domain\ValueObjects\{Identifier, Result};
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Enums\WeaponType;
use AqHub\Items\Domain\Entities\Weapon;

interface WeaponRepository
{
    /**
     * @return Result<Identifier|null>
     */
    public function persist(ItemInfo $itemInfo, WeaponType $type): Result;

    /**
     * @return Result<Weapon|null>
     */
    public function findByName(string $name): Result;
}
