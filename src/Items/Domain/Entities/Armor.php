<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Entities;

use AqHub\Shared\Domain\ValueObjects\{Result, Identifier};
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Items\Domain\Abstractions\AqwItem;

class Armor extends AqwItem
{
    private function __construct(
        Identifier $id,
        ItemInfo $info
    ) {
        $this->id   = $id;
        $this->info = $info;
    }

    /** @return Result<Armor> */
    public static function create(
        Identifier $id,
        ItemInfo $info,
    ) {
        return Result::success(null, new self($id, $info));
    }
}
