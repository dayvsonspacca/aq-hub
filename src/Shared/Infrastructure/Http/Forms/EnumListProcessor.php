<?php

declare(strict_types=1);

namespace AqHub\Shared\Infrastructure\Http\Forms;

use AqHub\Shared\Domain\Contracts\FromString;
use AqHub\Shared\Domain\Contracts\ToString;

class EnumListProcessor
{
    /**
     * @param ToString&FromString $enumClass
     */
    public static function fromComma(string $rawString, string $enumClass)
    {
        $list = explode(',', $rawString);
        sort($list);

        return array_map(
            fn ($value) => $enumClass::fromString($value)->getData(),
            array_filter($list, fn ($value) => $enumClass::fromString($value)->isSuccess())
        );
    }
}
