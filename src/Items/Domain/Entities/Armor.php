<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\Entities;

use AqWiki\Shared\Domain\ValueObjects\{Result, Identifier};
use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\Abstractions\AqwItem;

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
