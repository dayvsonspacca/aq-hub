<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\Repositories;

use AqWiki\Shared\Domain\ValueObjects\{Identifier, Result};
use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\Enums\WeaponType;
use AqWiki\Items\Domain\Entities\Weapon;

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
