<?php

declare(strict_types=1);

namespace AqWiki\Items\Domain\Entities;

use AqWiki\Items\Domain\ValueObjects\ItemInfo;
use AqWiki\Items\Domain\Abstractions\AqwItem;
use AqWiki\Shared\Domain\ValueObjects\Result;
use AqWiki\Shared\Domain\Enums\ResultStatus;
use AqWiki\Items\Domain\Enums\WeaponType;

class Weapon extends AqwItem
{
    private function __construct(
        string $guid,
        ItemInfo $info,
        private readonly WeaponType $type
    ) {
        $this->guid = $guid;
        $this->info = $info;
    }

    public function getType(): WeaponType
    {
        return $this->type;
    }

    /** @return Result<Weapon> */
    public static function create(
        string $guid,
        ItemInfo $info,
        WeaponType $type
    ) {
        $guid = trim($guid);
        if (empty($guid)) {
            return new Result(ResultStatus::Error, 'The GUID of an weapon cant be empty.', null);
        }

        return new Result(ResultStatus::Success, null, new self($guid, $info, $type));
    }
}
