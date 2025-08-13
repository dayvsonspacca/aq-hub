<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\Entities;

use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\Abstractions\AqwItem;
use AqWiki\Shared\Domain\ValueObjects\Result;

class Armor extends AqwItem
{
    private function __construct(
        string $guid,
        ItemInfo $info
    ) {
        $this->guid = $guid;
        $this->info = $info;
    }

    /** @return Result<Armor> */
    public static function create(
        string $guid,
        ItemInfo $info,
    ) {
        $guid = trim($guid);
        if (empty($guid)) {
            return Result::error('The GUID of an armor cant be empty.', null);
        }

        return Result::success(null, new self($guid, $info));
    }
}
