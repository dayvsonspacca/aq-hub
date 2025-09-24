<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Entities;

use AqHub\Shared\Domain\ValueObjects\{Result, StringIdentifier};
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Abstractions\AqwItem;
use AqHub\Items\Domain\Enums\WeaponType;

class Weapon extends AqwItem
{
    private function __construct(
        StringIdentifier $id,
        ItemInfo $info,
        private readonly WeaponType $type
    ) {
        $this->id   = $id;
        $this->info = $info;
    }

    /** @return Result<Weapon> */
    public static function create(
        StringIdentifier $id,
        ItemInfo $info,
        WeaponType $type
    ) {

        return Result::success(null, new self($id, $info, $type));
    }

    public function getType(): WeaponType
    {
        return $this->type;
    }
}
