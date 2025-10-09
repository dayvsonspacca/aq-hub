<?php

declare(strict_types=1);

namespace AqHub\Items\Infrastructure\Http\Presenters;

use AqHub\Items\Domain\Repositories\Data\ArmorData;

class ArmorPresenter
{
    /**
     * @param ArmorData[] $armorsData
     */
    public static function array(array $armorsData)
    {
        return array_map(fn($armorData) => $armorData->toArray(), $armorsData);
    }
}