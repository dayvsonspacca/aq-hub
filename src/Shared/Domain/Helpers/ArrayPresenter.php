<?php

declare(strict_types=1);

namespace AqHub\Shared\Domain\Helpers;

use AqHub\Shared\Domain\Abstractions\Data;

class ArrayPresenter
{
    public static function presentItem(Data $data): array
    {
        return $data->toArray();
    }

    /**
     * @param Data[] $data
     * */
    public static function presentCollection(array $data): array
    {
        return array_map(fn (Data $data) => $data->toArray(), $data);
    }
}
