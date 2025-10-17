<?php

declare(strict_types=1);

namespace AqHub\Items\Domain\Entities;

use AqHub\Core\Result;
use AqHub\Items\Domain\Abstractions\AqwItem;
use AqHub\Items\Domain\ValueObjects\ItemInfo;
use AqHub\Shared\Domain\ValueObjects\StringIdentifier;

class Helmet extends AqwItem
{
    private function __construct(
        StringIdentifier $id,
        ItemInfo $info
    ) {
        $this->id   = $id;
        $this->info = $info;
    }

    /** @return Result<Helmet> */
    public static function create(
        StringIdentifier $id,
        ItemInfo $info,
    ) {
        return Result::success(null, new self($id, $info));
    }
}
